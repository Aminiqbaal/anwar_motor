<?php

namespace App\Http\Controllers;

use App\Sparepart;
use App\Stock;
use App\Category;
use File;
use Auth;
use Illuminate\Http\Request;

class SparepartController extends Controller
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

        $spareparts = Sparepart::all();
        if(Auth::user()->role != 'mechanic') {
            foreach($spareparts as $key => $sparepart) {
                $sparepart->stock = Stock::where('sparepart_id', $sparepart->id)
                                            ->where('workshop_id', Auth::user()->workshop_id)
                                            ->sum('quantity');
                if($sparepart->stock == 0) unset($spareparts[$key]);
            }
        }

        return view('pages.sparepart.index_sparepart', compact('spareparts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sparepart  $sparepart
     * @return \Illuminate\Http\Response
     */
    public function show(Sparepart $sparepart)
    {
        Auth::user()->hasRole(['manager', 'cashier']);

        // dd($sparepart->stocks->groupBy('workshop_id'));
        return view('pages.sparepart.show_sparepart', compact('sparepart'));
    }

    /**
     * Display the specified resource by its name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        Auth::user()->hasRole(['manager']);

        $sparepart = Sparepart::firstWhere('name', $request->name);

        if(!is_null($sparepart)) {
            $sparepart->category = $sparepart->category->name;
            $sparepart->supplier = $sparepart->supplier->name;
            $sparepart->photo = asset('uploads/product/' . $sparepart->photo);
            $response = json_encode(['ok', $sparepart]);
        } else {
            $id = Sparepart::count() + (session()->has('wholesale_items') ? count(session()->get('wholesale_items')) + 1 : 1);
            $response = json_encode(['info', 'Suku cadang tidak ditemukan', $id]);
        }

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sparepart  $sparepart
     * @return \Illuminate\Http\Response
     */
    public function edit(Sparepart $sparepart)
    {
        Auth::user()->hasRole(['manager']);

        $categories = Category::all();
        $units = ['Unit', 'Set'];

        return view('pages.sparepart.edit_sparepart', compact('sparepart', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sparepart  $sparepart
     * @return \Illuminate\Http\Response
     */
    public function update_sparepart(Request $request, Sparepart $sparepart)
    {
        Auth::user()->hasRole(['manager']);

        $request->validate([
            'name' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required|in:Unit,Set',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'category' => 'required',
        ]);

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

        if($sparepart->save()) session()->flash('toast', ['success', 'Suku cadang berhasil diubah']);
        else session()->flash('toast', ['error', 'Suku cadang gagal diubah']);

        return redirect('/sparepart');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sparepart  $sparepart
     * @return \Illuminate\Http\Response
     */
    public function delete_sparepart(Sparepart $sparepart)
    {
        Auth::user()->hasRole(['manager']);

        if($sparepart->stock->delete()) session()->flash('toast', ['success', 'Suku cadang berhasil dihapus']);
        else session()->flash('toast', ['error', 'Suku cadang gagal dihapus']);

        if($sparepart->stocks->count() == 0) $sparepart->delete();

        return redirect('/sparepart');
    }
}
