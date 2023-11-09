<?php

namespace App\Http\Controllers;

use App\Service;
use Auth;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
        Auth::user()->hasRole(['manager', 'cashier']);

        $services = Service::all();

        return view('pages.service.index_service', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->hasRole(['manager']);

        return view('pages.service.create_service');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_service(Request $request)
    {
        Auth::user()->hasRole(['manager']);

        $request->validate([
            'name' => 'required',
            'cost' => 'required'
        ]);

        $service = new Service();
        $service->name = $request->name;
        $service->cost = str_replace('.', '', substr($request->cost, 3));

        if($service->save()) session()->flash('toast', ['success', 'Jasa berhasil ditambahkan']);
        else session()->flash('toast', ['error', 'Jasa gagal ditambahkan']);

        return redirect('/service');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        Auth::user()->hasRole(['manager']);

        return view('pages.service.edit_service', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update_service(Request $request, Service $service)
    {
        Auth::user()->hasRole(['manager']);

        $request->validate([
            'name' => 'required',
            'cost' => 'required'
        ]);

        $service->name = $request->name;
        $service->cost = str_replace('.', '', substr($request->cost, 3));

        if($service->save()) session()->flash('toast', ['success', 'Jasa berhasil diubah']);
        else session()->flash('toast', ['error', 'Jasa gagal diubah']);

        return redirect('/service');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function delete_service(Service $service)
    {
        Auth::user()->hasRole(['manager']);

        if($service->delete()) session()->flash('toast', ['success', 'Jasa berhasil dihapus']);
        else session()->flash('toast', ['error', 'Jasa gagal dihapus']);

        return redirect('/service');
    }
}
