<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Loan;
use App\Mechanic;
use App\Report;
use App\Salary;
use App\Service;
use App\ServiceTransactionItem;
use App\Sparepart;
use App\SparepartTransactionItem;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Validation\Rule;

class PengujianController extends Controller
{
    /**
     * Tambah Laporan Stok Rendah (Unit Test)
     * User Role: cashier
     */
    public function add_report()
    {
        $request = new Request([
            'sparepart_id' => 1
        ]);
        $pass = false;

        Auth::user()->hasRole(['cashier']);

        $validator = Validator::make($request->all(), [
            'sparepart_id' => 'exists:spareparts,id'
        ]);
        if($validator->fails()) return response()->json($validator->errors()->all());

        $check = Report::firstWhere([
            'sparepart_id' => $request->sparepart_id,
            'workshop_id'  => Auth::user()->workshop_id,
            'is_done'      => '0'
        ]);

        if($check) {
            session()->flash('toast', ['info', 'Suku cadang sudah pernah dilaporkan namun belum diproses']);

            $pass = true;
        } else {
            $report = new Report();
            $report->reported_at = \Carbon\Carbon::now()->format('Y-m-d');
            $report->sparepart_id = $request->sparepart_id;
            $report->workshop_id = Auth::user()->workshop_id;

            if($report->save()) session()->flash('toast', ['success', 'Suku cadang berhasil dilaporkan']);
            else session()->flash('toast', ['error', 'Suku cadang gagal dilaporkan']);

            $pass = true;
        }

        return $pass ? view('pages.pengujian') : 'gagal';
    }

    /**
     * Update Suku Cadang (Unit Test)
     * User Role: manager
     */
    public function update_sparepart()
    {
        $sparepart = Sparepart::firstWhere('name', 'Bearing Ball 6000');

        $request = new Request([
            'name' => 'Bearing Ball 6000',
            'quantity' => '30',
            'unit' => 'Unit',
            'purchase_price' => '12555',
            'selling_price' => '15500',
            'category' => 'All Motor',
        ]);
        $pass = false;

        Auth::user()->hasRole(['manager']);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required|in:Unit,Set',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'category' => 'required',
        ]);

        if($validator->fails()) return response()->json($validator->errors()->all());

        $sparepart->name = $request->name;
        $sparepart->unit = $request->unit;
        $sparepart->category_id = Category::firstWhere('name', $request->category) ? Category::firstWhere('name', $request->category)->id : Category::create(['name' => $request->category]);
        $sparepart->purchase_price = str_replace('.', '', substr($request->purchase_price, 3));
        $sparepart->selling_price = str_replace('.', '', substr($request->selling_price, 3));

        $sparepart->stock->quantity = $request->quantity;
        $sparepart->stock->save();

        if($request->has('photo')) {
            $file = $request->file('photo');
            $filename = $sparepart->id . '_' . str_replace([' ', '/'], '-', strtolower($sparepart->name)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("uploads\/product\\"), $filename);

            $sparepart->photo = $filename;
        }

        if($sparepart->save()) $pass = true;

        return $pass ? view('pages.pengujian') : 'gagal';
    }

    /**
     * Update Mekanik (Unit Test)
     * User Role: manager
     */
    public function update_mechanic()
    {
        $user = User::find(7);
        $request = new Request([
            'name'         => 'Mubin',
            'worked_since' => '2000',
            'age'          => 30,
            'address'      => 'Pongangan, Gresik'
        ]);
        $pass = false;

        Auth::user()->hasRole(['manager']);
        $user->role == 'mechanic' && $user->workshop_id == Auth::user()->workshop_id ? : abort('403');

        $validator = Validator::make($request->all(), [
            'name'         => ['required', Rule::unique('mechanics', 'name')->ignore($user->data, 'name')],
            'worked_since' => 'required|numeric|gt:0',
            'age'          => 'required|numeric|gt:0',
            'address'      => 'required'
        ]);
        if($validator->fails()) return response()->json($validator->errors()->all());

        $user->username = str_replace(' ', '', strtolower($request->name));
        $user->save();

        $data = Mechanic::firstWhere('user_id', $user->id);
        $data->name = ucwords($request->name);
        $data->worked_since = $request->worked_since;
        $data->age = $request->age;
        $data->address = ucwords($request->address);

        if($data->save()) $pass = true;

        return $pass ? view('pages.pengujian') : 'gagal';
    }


    /**
     * Transaksi Penjualan (Integration Test)
     * User Role: cashier
     */
    public function add_transaction()
    {
        $sparepart = Sparepart::firstWhere('name', 'Bearing Ball 6300');
        $sparepart->qty = 1;
        session()->push('spareparts', $sparepart);

        $service = Service::firstWhere('name', 'Ganti Bearing 6300 s/d 6302');
        session()->push('services', $service);

        $request = new Request([
            'grand_total' => 33000,
            'customer_id' => 1,
            'mechanic_id' => 7
        ]);
        $pass = false;

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
        $transaction->workshop_id = Auth::user()->workshop_id;
        $transaction->created_at = \Carbon\Carbon::now()->format('Y-m-d');

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

                $salary = new Salary();
                $salary->salary = $this->service_subtotal();
                $salary->user_id = $request->mechanic_id;
                $salary->transaction_id = $transaction->id;
                $salary->save();

                $this->clear_services();
            }
            $pass = true;
        }

        return $pass ? view('pages.pengujian') : 'gagal';
    }

    public function clear_spareparts()
    {
        session()->forget('spareparts');
    }

    public function clear_services()
    {
        session()->forget('services');
    }

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
     * Cicil Hutang & Potong Gaji (Integration Test)
     * User Role: manager
     */
    public function cut_salary()
    {
        $request = new Request([
            'salary_id' => 1,
            'loan_id' => 1,
            'installment' => 1
        ]);
        $pass = false;

        Auth::user()->hasRole(['manager']);

        $salary = Salary::find($request->salary_id);
        $loan = Loan::find($request->loan_id);

        $installment_amount = $request->installment * $loan->cut_amount;

        $loan->remaining -= $installment_amount;
        if($loan->remaining == 0) $loan->is_paid = '1';

        $salary->cutted_salary = ($salary->cutted_salary ?? $salary->salary) - $installment_amount;

        if($loan->save() && $salary->save()) $pass = true;

        return $pass ? view('pages.pengujian') : 'gagal';
    }
}
