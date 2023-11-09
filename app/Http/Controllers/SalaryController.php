<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Salary;
use App\Transaction;
use App\User;
use Auth;
use Illuminate\Http\Request;

class SalaryController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Auth::user()->hasRole(['mechanic', 'manager']);

        $mechanic_id = Auth::user()->role == 'mechanic' ? Auth::id() : $request->id;
        $mechanic = User::firstWhere('id', $mechanic_id);
        $transactions = Transaction::where('mechanic_id', $mechanic_id)->get();
        $untaken_salary = Salary::where([
                'user_id' => $mechanic_id,
                'taken_at' => null
            ])->get()
            ->sum(function($salary){
                return $salary->salary - $salary->cuts;
            });

        if(Auth::user()->role == 'mechanic') {
            return view('pages.salary.index_salary', compact('transactions', 'mechanic', 'untaken_salary'));
        } elseif(Auth::user()->role == 'manager') {
            if($request->has('id')) {
                User::firstWhere('id', $request->id)->workshop_id == Auth::user()->workshop_id ? : abort('403');
                $loans = Loan::where([
                    'user_id' => $request->id,
                    'is_approved' => '1',
                    'is_paid' => '0'
                ])->get();
            return view('pages.salary.index_salary', compact('mechanic', 'transactions', 'loans', 'untaken_salary'));
        } else {
                $mechanics = User::where([
                        'role'        => 'mechanic',
                        'workshop_id' => Auth::user()->workshop_id
                    ])->get();
                return view('pages.salary.choose_salary', compact('mechanics'));
            }
        }
    }

    /**
     * Display a listing of the resource with filter.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function filter(Request $request)
    {
        Auth::user()->hasRole(['mechanic', 'manager']);

        $mechanic_id = Auth::user()->role == 'mechanic' ? Auth::id() : $request->mechanic_id;

        if($request->date != '') {
            $date = date_create($request->date);
            $transactions = Transaction::whereMonth('created_at', $date->format('m'))
                ->whereYear('created_at', $date->format('Y'))
                ->where('mechanic_id', $mechanic_id)
                ->get();
        } else {
            $transactions = Transaction::where('mechanic_id', $mechanic_id)->get();
        }

        return view('pages.salary.list_salary', compact('transactions'));
    }

    public function get_untaken_salary(Request $request) {
        Auth::user()->hasRole(['mechanic', 'manager']);

        $user_id = Auth::user()->role == 'mechanic' ? Auth::id() : $request->mechanic_id;

        if($request->date != '') {
            $date = date_create($request->date);
            $untaken_salary = Salary::where([
                'user_id' => $user_id,
                'taken_at' => null,
            ])->whereMonth('date', $date->format('m'))
            ->whereYear('date', $date->format('Y'))
            ->get()
            ->sum(function($salary){
                return $salary->salary - $salary->cuts;
            });
        } else {
            $untaken_salary = Salary::where([
                    'user_id' => $user_id,
                    'taken_at' => null
                ])->get()->sum(function($salary){
                    return $salary->salary - $salary->cuts;
                });
        }

        return number_format($untaken_salary, 0, ',', '.');
    }

    public function is_taken(Request $request) {
        Auth::user()->hasRole(['manager']);

        $date = date_create($request->date);
        $salary = Salary::where([
            'user_id' => $request->mechanic_id,
            'taken_at' => null,
        ])->whereMonth('date', $date->format('m'))
        ->whereYear('date', $date->format('Y'))
        ->first();

        return $salary != null ? 'untaken' : 'taken';
    }

    public function get_salary_id(Request $request) {
        Auth::user()->hasRole(['manager']);

        $date = date_create($request->date);
        $salary = Salary::where('user_id', $request->mechanic_id)->whereNotNull('taken_at')->whereMonth('date', $date->format('m'))
        ->whereYear('date', $date->format('Y'))
        ->first();

        return $salary;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        Auth::user()->hasRole(['manager', 'mechanic']);
        $transaction->workshop_id == Auth::user()->workshop_id ? : abort('403');

        return view('pages.salary.show_salary', compact('transaction'));
    }

    /**
     * Export to PDF.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function pdf_export(Salary $salary)
    {
        Auth::user()->hasRole(['manager']);
        Auth::user()->workshop_id == $salary->user->workshop_id ? : abort('403');

        $date = date_create($salary->date);
        $transactions = Transaction::whereMonth('created_at', $date->format('m'))
                ->whereYear('created_at', $date->format('Y'))
                ->where('mechanic_id', $salary->user_id)
                ->get();

        $loans = Loan::whereMonth('created_at', $date->format('m'))
        ->whereYear('created_at', $date->format('Y'))
        ->where([
            'user_id' => $salary->user_id,
            'is_approved' => '1',
            'is_paid' => '1'
        ])
        ->get();

        return view('pages.salary.pdf_salary', compact('salary', 'transactions', 'loans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update_salary(Request $request, Salary $salary)
    {
        Auth::user()->hasRole(['manager']);

        $salary->taken_at = \Carbon\Carbon::now();

        if($salary->save()) session()->flash('toast', ['success', 'Gaji berhasil diambil']);
        else session()->flash('toast', ['error', 'Gaji gagal diambil']);

        return redirect('/salary?id=' . $salary->user_id);
    }

    /**
     * Take salary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function take(Request $request)
    {
        $date = date_create($request->date);
        $salary =
            Salary::where([
                'user_id' => $request->mechanic_id,
                'taken_at' => null,
            ])->whereMonth('date', $date->format('m'))
            ->whereYear('date', $date->format('Y'))
            ->first();

        if($salary) {
            $salary->taken_at = \Carbon\Carbon::now();
            $salary->save();

            echo 'success';
        } else {
            echo 'failed';
        }
    }

    /**
     * Cut salary and update loan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cut(Request $request)
    {
        Auth::user()->hasRole(['manager']);

        $salary = Salary::find($request->salary_id);
        $loan = Loan::find($request->loan_id);

        $installment_amount = ($loan->amount * $loan->percentage / 100);
        if($request->installment == 'full') {
            $installment_pay = $loan->remaining;
        } else {
            $installment_pay = $request->installment * $installment_amount;
        }

        $loan->decrement('remaining', $installment_pay);
        if($loan->remaining == 0) {
            $loan->is_paid = '1';
            $loan->save();
        }

        if($salary->cuts == null) {
            $salary->cuts = $installment_pay;
            $salary->save();
        } else {
            $salary->increment('cuts', $installment_pay);
        }

        return redirect('/salary?id=' . $salary->user_id);
    }
}
