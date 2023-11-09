<?php

namespace App\Http\Controllers;

use App\User;
use App\Loan;
use App\Salary;
use App\Notification;
use Auth;
use Illuminate\Http\Request;

class LoanController extends Controller
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
        Auth::user()->hasRole(['manager', 'mechanic']);

        if(Auth::user()->role == 'mechanic') $loans = Loan::where('user_id', Auth::id())->get();
        elseif(Auth::user()->role == 'manager') $loans = Loan::where('workshop_id', Auth::user()->workshop_id)->get();

        return view('pages.loan.index_loan', compact('loans'));
    }

    /**
     * Display a listing of the resource with filter.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function filter(Request $request)
    {
        Auth::user()->hasRole(['manager']);

        if($request->date != '') {
            $date = date_create($request->date);
            $loans = Loan::whereMonth('created_at', $date->format('m'))
                ->whereYear('created_at', $date->format('Y'))
                ->where([
                    'user_id' => $request->mechanic_id,
                    'is_approved' => '1',
                    'is_paid' => '0'
                ])
                ->get();
        } else {
            $loans = Loan::where([
                    'user_id' => $request->mechanic_id,
                    'is_approved' => '1',
                    'is_paid' => '0'
                ])->get();
        }

        return view('pages.loan.list_loan', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->hasRole(['mechanic']);

        $percentages = ['10', '20', '30', '40', '50'];

        return view('pages.loan.create_loan', compact('percentages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_loan(Request $request)
    {
        Auth::user()->hasRole(['mechanic']);

        $request->validate([
            'created_at' => 'required',
            'amount'     => 'required',
            'percentage' => 'required|in:10,20,30,40,50',
            'info'       => 'required'
        ]);

        $loan = new Loan();
        $loan->amount = str_replace('.', '', substr($request->amount, 3));
        $loan->remaining = $loan->amount;
        $loan->percentage = $request->percentage;
        $loan->info = ucfirst($request->info);
        $loan->created_at = $request->created_at;
        $loan->user_id = Auth::id();
        $loan->workshop_id = Auth::user()->workshop_id;

        $notification = new Notification();
        $notification->message = 'Pengajuan pinjaman dari ' . Auth::user()->data->name . ' sebesar ' . str_replace(' ', '', $request->amount) . '.';
        $notification->target_user_id = User::where([
            'workshop_id' => Auth::user()->workshop_id,
            'role' => 'manager'
        ])->first()->id;
        $notification->redirect = '/loan';

        if($loan->save() && $notification->save()) session()->flash('toast', ['success', 'Pinjaman berhasil diajukan']);
        else session()->flash('toast', ['error', 'Pinjaman gagal diajukan']);

        return redirect('/loan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Auth::user()->hasRole(['manager', 'mechanic']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        Auth::id() == $loan->user_id && $loan->is_approved == '0' ? : abort('403');

        $percentages = ['10', '20', '30', '40', '50'];

        return view('pages.loan.edit_loan', compact('loan', 'percentages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update_loan(Request $request, Loan $loan)
    {
        Auth::user()->hasRole(['manager', 'mechanic']);

        if(Auth::user()->role == 'mechanic') {
            $request->validate([
                'amount'     => 'required',
                'percentage' => 'required|in:10,20,30,40,50',
                'info'       => 'required'
            ]);

            $loan->amount = str_replace('.', '', substr($request->amount, 3));
            $loan->percentage = $request->percentage;
            $loan->info = $request->info;

            if($loan->save()) session()->flash('toast', ['success', 'Pinjaman berhasil diubah']);
            else session()->flash('toast', ['error', 'Pinjaman gagal diubah']);
        } elseif(Auth::user()->role == 'manager') {
            $loan->is_approved = $request->is_approved;

            $notification = new Notification();
            $notification->message = 'Pengajuan pinjaman Anda ';
            $notification->message .= $loan->is_approved == 1 ? 'diterima' : 'ditolak.';
            $notification->redirect = '/loan';
            $notification->target_user_id = $loan->user_id;

            if($loan->save() && $notification->save()) session()->flash('toast', ['success', 'Status pinjaman berhasil diubah']);
            else session()->flash('toast', ['error', 'Status pinjaman gagal diubah']);
        }

        return redirect('/loan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function delete_loan(Loan $loan)
    {
        Auth::id() == $loan->user_id && $loan->is_approved == '0' ? : abort('403');

        if($loan->delete()) session()->flash('toast', ['success', 'Pinjaman berhasil dihapus']);
        else session()->flash('toast', ['error', 'Pinjaman gagal dihapus']);

        return redirect('/loan');
    }

    /**
     * Installment modal.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function pay(Loan $loan)
    {
        Auth::user()->hasRole(['manager']);

        $date = date_create($loan->created_at);
        $salary = Salary::where([
                'user_id' => $loan->user_id,
                'taken_at' => null
            ])->whereMonth('date', $date->format('m'))
            ->whereYear('date', $date->format('Y'))
            ->first();
            dd($salary);
        $installment_amount = ($loan->amount * $loan->percentage / 100);
        $from_salaries = is_null($salary->cuts) ? $salary->salary / $installment_amount : ($salary->salary - $salary->cuts) / $installment_amount;
        $from_loan = $loan->remaining / $installment_amount;

        $loan->installment = min($from_salaries, $from_loan);

        return view('pages.loan.pay_loan', compact('loan', 'salary'));
    }
}
