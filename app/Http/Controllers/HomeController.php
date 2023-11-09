<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Loan;
use App\Salary;
use App\Wholesale;
use Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->role == 'manager') {
            $chart_data = array();

            $transactions = Transaction::where('workshop_id', Auth::user()->workshop_id)->orderBy('created_at')->get();
            foreach ($transactions as $transaction) {
                $date = date_format(date_create($transaction->created_at), 'M Y');
                if(array_key_exists($date, $chart_data)) {
                    if(!array_key_exists('selling', $chart_data[$date])) $chart_data[$date]['selling'] = 0;
                } else $chart_data[$date]['selling'] = 0;

                $chart_data[$date]['selling'] +=
                    $transaction->spareparts->sum(function($item) {
                        return $item->sparepart->selling_price * $item->quantity;
                    });
            }

            $wholesales = Wholesale::where('workshop_id', Auth::user()->workshop_id)->where('grand_total', '!=', '0')->orderBy('purchased_at')->get();
            foreach ($wholesales as $wholesale) {
                $date = date_format(date_create($wholesale->purchased_at), 'M Y');
                if(array_key_exists($date, $chart_data)) {
                    if(!array_key_exists('purchase', $chart_data[$date])) $chart_data[$date]['purchase'] = 0;
                } else $chart_data[$date]['purchase'] = 0;

                $chart_data[$date]['purchase'] +=
                    $wholesale->items->sum(function($item){
                        return $item->purchase_price * $item->quantity;
                    });
            }

            // foreach ($chart_data as $key => $data) {
            //     if(isset($data['selling']) && isset($data['purchase'])) {
            //         if($data['selling'] == 0 && $data['purchase'] == 0) unset($chart_data[$key]);
            //     } else unset($chart_data[$key]);
            // }

            return view('pages.dashboard.manager_dashboard', compact('chart_data'));
        } elseif(Auth::user()->role == 'cashier') {
            return redirect('/transaction');
        } elseif(Auth::user()->role == 'mechanic') {
            $chart_data = array();

            $salaries = Salary::where('user_id', Auth::id())->get();
            foreach ($salaries as $salary) {
                $date = date_format(date_create($salary->date), 'M Y');
                if(array_key_exists($date, $chart_data)) {
                    if(!array_key_exists('salary', $chart_data[$date])) $chart_data[$date]['salary'] = 0;
                } else $chart_data[$date]['salary'] = 0;

                $chart_data[$date]['salary'] += $salary->cuts ? $salary->salary - $salary->cuts : $salary->salary;
            }

            $loans = Loan::where([
                    'user_id' => Auth::id(),
                    'is_approved' => '1',
                    'is_paid'     => '0'
                ])->get();
            if(count($loans) > 0) {
                foreach ($loans as $loan) {
                    $date = date_format(date_create($loan->created_at), 'M Y');
                    if(array_key_exists($date, $chart_data)) {
                        if(!array_key_exists('loan', $chart_data[$date])) $chart_data[$date]['loan'] = 0;
                    } else $chart_data[$date]['loan'] = 0;
                    $chart_data[$date]['loan'] += $loan->remaining;
                }
            }

            foreach ($chart_data as $key => $data) {
                if($data['salary'] == 0 && $data['loan'] == 0) unset($chart_data[$key]);
            }

            return view('pages.dashboard.mechanic_dashboard', compact('chart_data'));
        }
    }
}
