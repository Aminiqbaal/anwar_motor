<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Sparepart;
use App\SparepartTransactionItem;
use App\Service;
use App\ServiceTransactionItem;
use App\Salary;
use App\User;
use App\Customer;
use App\Report;
use App\Notification;
use Auth;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::user()->hasRole(['cashier']);

        $transactions = Transaction::where('workshop_id', Auth::user()->workshop_id)->get();

        return view('pages.transaction.index_transaction', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->hasRole(['cashier']);

        $spareparts = Sparepart::all();
        $services = Service::all();
        $mechanics = User::where('role', 'mechanic')->where('workshop_id', Auth::user()->workshop_id)->get();
        $customers = Customer::all();

        return view('pages.transaction.create_transaction', compact('spareparts', 'services', 'customers', 'mechanics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_transaction(Request $request)
    {
        Auth::user()->hasRole(['cashier']);

        $request->validate([
            'grand_total' => 'required|numeric|gt:0',
            'customer_id' => 'required|exists:customers,id',
            'mechanic_id' => [
                function($attribute, $value, $fail) {
                    if(session()->has('services')) {
                        if(is_null($value)) {
                            $fail('The mechanic field is required.');
                        }
                    }
                }
            ]
        ]);

        $transaction = new Transaction();
        $transaction->grand_total = $request->grand_total;
        $transaction->customer_id = $request->customer_id;
        $transaction->mechanic_id = $request->mechanic_id ?? null;
        $transaction->workshop_id = Auth::user()->workshop_id;
        $transaction->created_at = $request->created_at;

        if($transaction->save()) {
            session()->flash('toast', ['success', 'Transaksi berhasil']);

            if(session()->has('spareparts')) {
                foreach (session()->get('spareparts') as $sparepart) {
                    $item = new SparepartTransactionItem();
                    $item->sparepart_id = $sparepart->id;
                    $item->quantity = $sparepart->qty;
                    $item->transaction_id = $transaction->id;
                    $item->save();

                    $sparepart->stock->decrement('quantity', $sparepart->qty);
                    // $sparepart->stock->save();

                    if($sparepart->stock->quantity <= 10) {
                        $report = new Report();
                        $report->reported_at = \Carbon\Carbon::now()->format('Y-m-d');
                        $report->sparepart_id = $sparepart->id;
                        $report->workshop_id = Auth::user()->workshop_id;
                        $report->save();

                        $notification = new Notification();
                        $notification->message = 'Laporan Stok Rendah: '. $sparepart->name .'.';
                        $notification->target_user_id = User::where([
                            'workshop_id' => Auth::user()->workshop_id,
                            'role' => 'manager'
                        ])->first()->id;
                        $notification->redirect = '/report';
                        $notification->save();
                    }
                }
                $this->clear_spareparts();
            }

            if(session()->has('services')) {
                $request->validate([
                    'mechanic_id' => 'exists:users,id'
                ]);
                foreach (session()->get('services') as $service) {
                    $item = new ServiceTransactionItem();
                    $item->service_id = $service->id;
                    $item->transaction_id = $transaction->id;
                    $item->save();
                }

                $date = \Carbon\Carbon::parse($transaction->created_at);
                $salary = Salary::whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))->where('user_id', $request->mechanic_id)->first();
                if($salary) {
                    $salary->increment('salary', $this->service_subtotal());
                } else {
                    $salary = new Salary();
                    $salary->salary = $this->service_subtotal();
                    $salary->date = \Carbon\Carbon::now()->format('Y-m-01');
                    $salary->user_id = $request->mechanic_id;
                    $salary->save();
                }

                $this->clear_services();
            }
        } else session()->flash('toast', ['error', 'Transaksi gagal']);

        return redirect('/transaction');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        Auth::user()->hasRole(['cashier']);

        return view('pages.transaction.show_transaction', compact('transaction'));
    }

    /**
     * Export to PDF.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function pdf_export(Transaction $transaction)
    {
        Auth::user()->hasRole(['cashier']);
        Auth::user()->workshop_id == $transaction->workshop->id ? : abort('403');

        return view('pages.transaction.pdf_transaction', ['transaction' => $transaction]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        Auth::user()->hasRole(['cashier']);
        Auth::user()->workshop_id == $transaction->workshop->id ? : abort('403');

        $spareparts = Sparepart::all();
        $services = Service::all();
        $mechanics = User::where('role', 'mechanic')->where('workshop_id', Auth::user()->workshop_id)->get();
        $customers = Customer::all();

        $this->clear_spareparts();
        $this->clear_services();

        foreach ($transaction->spareparts as $item) {
            $sparepart = $item->sparepart;
            $sparepart->qty = $item->quantity;
            $sparepart->current_stock = $sparepart->stock->quantity + $item->quantity;

            if($sparepart->current_stock > 10) {
                $report = Report::where([
                    'reported_at' => $transaction->created_at,
                    'sparepart_id' => $sparepart->id,
                    'workshop_id' => Auth::user()->workshop_id,
                    'is_done' => '0'
                ]);
                $report->delete();
            }

            session()->has('spareparts') ? : session()->put('spareparts', []);
            session()->push('spareparts', $sparepart);
        }
        foreach ($transaction->services as $item) {
            $service = $item->service;
            session()->has('services') ? : session()->put('services', []);
            session()->push('services', $service);
        }

        return view('pages.transaction.edit_transaction', compact('transaction', 'spareparts', 'services', 'customers', 'mechanics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update_transaction(Request $request, Transaction $transaction)
    {
        Auth::user()->hasRole(['cashier']);

        $request->validate([
            'grand_total' => 'required|numeric|gt:0',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $old_services_total = $transaction->services->sum(function($item){
            return $item->service->cost;
        });
        $date = \Carbon\Carbon::parse($transaction->created_at);
        $salary = Salary::whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))->where('user_id', $transaction->mechanic_id)->first();
        if($salary)
            $salary->decrement('salary', $old_services_total);

        $transaction->grand_total = $request->grand_total;
        $transaction->mechanic_id = $request->mechanic_id ?? $transaction->mechanic_id;
        $transaction->customer_id = $request->customer_id;
        $transaction->created_at = $request->created_at;

        if($transaction->save()) session()->flash('toast', ['success', 'Transaksi berhasil diubah']);
        else session()->flash('toast', ['error', 'Transaksi gagal diubah']);

        $old_spareparts = $transaction->spareparts;
        SparepartTransactionItem::where('transaction_id', $transaction->id)->delete();
        if(session()->has('spareparts')) {
            foreach (session()->get('spareparts') as $sparepart) {
                $item = new SparepartTransactionItem();
                $item->sparepart_id = $sparepart->id;
                $item->quantity = $sparepart->qty;
                $item->transaction_id = $transaction->id;
                $item->save();

                $sparepart->qty -= $old_spareparts->firstWhere('sparepart_id', $sparepart->id) ? $old_spareparts->firstWhere('sparepart_id', $sparepart->id)->quantity : 0;
                $sparepart->stock->quantity -= $sparepart->qty;
                $sparepart->stock->save();
            }
            foreach ($old_spareparts as $item) { //find if old sparepart is stay in cart
                $found = false;
                foreach (session()->get('spareparts') as $sparepart) {
                    if($item->sparepart_id == $sparepart->id) {
                        $found = true; break;
                    }
                }
                if(!$found) { //return stock before if the old sparepart deleted
                    $sparepart = Sparepart::firstWhere('id', $item->sparepart_id);
                    $sparepart->stock->quantity += $item->quantity;
                    $sparepart->stock->save();
                }
            }
            $this->clear_spareparts();
        } else {
            foreach ($old_spareparts as $item) { //return stock before if no sparepart in cart
                $sparepart = Sparepart::firstWhere('id', $item->sparepart_id);
                $sparepart->stock->quantity += $item->quantity;
                $sparepart->stock->save();
            }
        }

        ServiceTransactionItem::where('transaction_id', $transaction->id)->delete();
        if(session()->has('services')) {
            $request->validate([
                'mechanic_id' => 'exists:users,id'
            ]);
            foreach (session()->get('services') as $service) {
                $item = new ServiceTransactionItem();
                $item->service_id = $service->id;
                $item->transaction_id = $transaction->id;
                $item->save();
            }

            if($salary->user_id == $transaction->mechanic_id) {
                $salary->increment('salary', $this->service_subtotal());
            } else {
                $salary = Salary::whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))->where('user_id', $request->mechanic_id)->first();
                if($salary) {
                    $salary->increment('salary', $this->service_subtotal());
                } else {
                    $salary = new Salary();
                    $salary->salary = $this->service_subtotal();
                    $salary->date = \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-01');
                    $salary->user_id = $request->mechanic_id;
                    $salary->save();
                }
            }

            $this->clear_services();
        }

        return redirect('/transaction');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function delete_transaction(Transaction $transaction)
    {
        Auth::user()->hasRole(['cashier']);

        $old_services_total = $transaction->services->sum(function($item){
            return $item->service->cost;
        });
        $date = \Carbon\Carbon::parse($transaction->created_at);
        $salary = Salary::whereMonth('date', $date->format('m'))->whereYear('date', $date->format('Y'))->where('user_id', $transaction->mechanic_id)->first();
        if($salary)
            $salary->decrement('salary', $old_services_total);

        $old_spareparts = $transaction->spareparts;
        foreach ($old_spareparts as $item) {
            $sparepart = Sparepart::find($item->sparepart_id);
            $sparepart->stock->quantity += $item->quantity;
            $sparepart->stock->save();
        }

        if($transaction->delete()) session()->flash('toast', ['success', 'Transaksi berhasil dihapus']);
        else session()->flash('toast', ['error', 'Transaksi gagal dihapus']);

        return redirect('/transaction');
    }


    // AJAX
    /**
     * Get grand total.
     */
    public function grand_total()
    {
        $total = 0;
        $total += $this->sparepart_subtotal();
        $total += $this->service_subtotal();

        return $total;
    }

    /**
     * Get sparepart cart.
     */
    public function get_sparepart()
    {
        $spareparts = session()->get('spareparts') ?? array();

        return view('pages.transaction.sparepart_list_transaction', compact('spareparts'));
    }

    /**
     * Get sparepart subtotal.
     */
    public function sparepart_subtotal()
    {
        $spareparts = session()->get('spareparts') ?? array();
        $subtotal = 0;
        foreach ($spareparts as $sparepart) {
            $subtotal += $sparepart->selling_price * $sparepart->qty;
        }

        return $subtotal;
    }

    /**
     * Add sparepart to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function add_sparepart(Request $request)
    {
        $sparepart_name = $request->sparepart;
        $sparepart = Sparepart::firstWhere('name', $sparepart_name);
        $sparepart->qty = 1;
        $sparepart->current_stock = $sparepart->stock->quantity;

        session()->has('spareparts') ? : session()->put('spareparts', []);

        $check = collect(session()->get('spareparts'))->where('id', $sparepart->id);
        if(count($check) > 0) echo json_encode(['info', 'Suku cadang sudah ada di keranjang']);
        else session()->push('spareparts', $sparepart);
    }

    /**
     * Increment quantity of specific sparepart on cart.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function inc_sparepart(Request $request)
    {
        $spareparts = session()->get('spareparts');
        foreach ($spareparts as $key => $sparepart) {
            if($key == $request->key) $spareparts[$key]->qty++;
        }

        session()->put('spareparts', $spareparts);
    }

    /**
     * Decrement quantity of specific sparepart on cart.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function dec_sparepart(Request $request)
    {
        $spareparts = session()->get('spareparts');
        foreach ($spareparts as $key => $sparepart) {
            if($key == $request->key) $spareparts[$key]->qty--;
        }

        session()->put('spareparts', $spareparts);
    }

    /**
     * Delete specific sparepart from cart.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function delete_sparepart(Request $request)
    {
        $spareparts = session()->get('spareparts');
        foreach ($spareparts as $key => $sparepart) {
            if($key == $request->key) unset($spareparts[$key]);
        }

        count($spareparts) > 0 ? session()->put('spareparts', $spareparts) : session()->forget('spareparts');
    }

    /**
     * Clear sparepart cart.
     */
    public function clear_spareparts()
    {
        session()->forget('spareparts');
    }

    /**
     * Get service cart.
     */
    public function get_service()
    {
        $services = session()->get('services') ?? array();

        return view('pages.transaction.service_list_transaction', compact('services'));
    }

    /**
     * Get service subtotal.
     */
    public function service_subtotal()
    {
        $services = session()->get('services') ?? array();
        $subtotal = 0;
        foreach ($services as $service) {
            $subtotal += $service->cost;
        }

        return $subtotal;
    }

    /**
     * Add service to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function add_service(Request $request)
    {
        $service_name = $request->service;
        $service = Service::firstWhere('name', $service_name);

        session()->has('services') ? : session()->put('services', []);

        $check = collect(session()->get('services'))->where('id', $service->id);
        if(count($check) > 0) echo json_encode(['info', 'Jasa sudah ada di keranjang']);
        else session()->push('services', $service);
    }

    /**
     * Delete specific service from cart.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function delete_service(Request $request)
    {
        $services = session()->get('services');
        foreach ($services as $key => $service) {
            if($key == $request->key) unset($services[$key]);
        }

        count($services) > 0 ? session()->put('services', $services) : session()->forget('services');
    }

    /**
     * Clear service cart.
     */
    public function clear_services()
    {
        session()->forget('services');
    }
}
