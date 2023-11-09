<?php

namespace App\Http\Controllers;

use App\Customer;
use Auth;
use Illuminate\Http\Request;

class CustomerController extends Controller
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

        $customers = Customer::all();

        return view('pages.customer.index_customer', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->hasRole(['cashier']);

        return view('pages.customer.create_customer');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_customer(Request $request)
    {
        Auth::user()->hasRole(['cashier']);

        $request->validate([
            'name'  => 'required',
            'phone' => 'required|numeric',
        ]);

        $customer = new Customer();
        $customer->name = ucwords($request->name);
        $customer->phone = $request->phone;

        if($customer->save()) session()->flash('toast', ['success', 'Pelanggan berhasil ditambahkan']);
        else session()->flash('toast', ['error', 'Pelanggan gagal ditambahkan']);

        return redirect('/customer');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        Auth::user()->hasRole(['cashier']);

        return view('pages.customer.edit_customer', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update_customer(Request $request, Customer $customer)
    {
        Auth::user()->hasRole(['cashier']);

        $request->validate([
            'name'  => 'required',
            'phone' => 'required|numeric',
        ]);

        $customer->name = $request->name;
        $customer->phone = $request->phone;

        if($customer->save()) session()->flash('toast', ['success', 'Pelanggan berhasil diubah']);
        else session()->flash('toast', ['error', 'Pelanggan gagal diubah']);

        return redirect('/customer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function delete_customer(Customer $customer)
    {
        Auth::user()->hasRole(['cashier']);

        if($customer->delete()) session()->flash('toast', ['success', 'Pelanggan berhasil dihapus']);
        else session()->flash('toast', ['error', 'Pelanggan gagal dihapus']);

        return redirect('/customer');
    }
}
