<?php

namespace App\Http\Controllers;

use App\Wholesale;
use App\Sparepart;
use App\Category;
use App\Supplier;
use App\Stock;
use App\Report;
use App\WholesaleItem;
use Auth;
use Illuminate\Http\Request;

class WholesaleController extends Controller
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
        Auth::user()->hasRole(['manager']);

        $wholesales = Wholesale::where('grand_total', '>', '0')
                                ->where('workshop_id', Auth::user()->workshop_id)
                                ->get();

        return view('pages.wholesale.index_wholesale', compact('wholesales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->hasRole(['manager']);

        $spareparts = Sparepart::all();
        $categories = Category::all();
        $suppliers = Supplier::all();
        $units = ['Unit', 'Set'];
        $reports = Report::where([
            'workshop_id' => Auth::user()->workshop_id,
            'is_done' => '0'
        ])->get();

        return view('pages.wholesale.create_wholesale', compact('spareparts', 'categories', 'suppliers', 'units', 'reports'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_wholesale(Request $request)
    {
        Auth::user()->hasRole(['manager']);

        $request->validate([
            'supplier' => 'required',
            'date' => 'required',
            'grand_total' => 'required|numeric|gt:0',
            'receipt' => 'required',
        ]);

        $file = $request->file('receipt');
        $filename = Wholesale::count() + 1 . '_' . $request->date . '.' . $file->getClientOriginalExtension();
        $file->move(public_path("uploads\/receipt\\"), $filename);

        $wholesale = new Wholesale();
        $wholesale->purchased_at = $request->date;
        $wholesale->photo = $filename;
        $wholesale->grand_total = $request->grand_total;
        $wholesale->workshop_id = Auth::user()->workshop_id;

        if($wholesale->save()) session()->flash('toast', ['success', 'Data pembelian berhasil ditambahkan']);
        else session()->flash('toast', ['error', 'Data pembelian gagal ditambahkan']);

        if(session()->has('wholesale_items')) {
            foreach (session()->get('wholesale_items') as $item) {
                if(is_null(Sparepart::firstWhere('id', $item->id))) {
                    $sparepart = new Sparepart();
                    $sparepart->id = $item->id;
                    $sparepart->name = $item->name;
                    $sparepart->unit = $item->unit;
                    $sparepart->purchase_price = $item->purchase_price;
                    $sparepart->selling_price = $item->selling_price;
                    $sparepart->category_id = Category::firstWhere('name', $item->category) ? Category::firstWhere('name', $item->category)->id : Category::create(['name' => $item->category]);
                    $sparepart->supplier_id = Supplier::firstWhere('name', $item->supplier) ? Supplier::firstWhere('name', $item->supplier)->id : Supplier::create(['name' => $item->supplier]);
                    $sparepart->photo = $item->photo;
                    $sparepart->save();
                } else {
                    $sparepart = Sparepart::firstWhere('id', $item->id);
                    $sparepart->photo = $item->photo;
                    $sparepart->save();
                }

                $stock = new Stock();
                $stock->sparepart_id = $sparepart->id;
                $stock->workshop_id = Auth::user()->workshop_id;
                $stock->quantity = $item->quantity;
                $stock->save();

                $wholesale_item = new WholesaleItem();
                $wholesale_item->name = $item->name;
                $wholesale_item->quantity = $item->quantity;
                $wholesale_item->unit = $item->unit;
                $wholesale_item->purchase_price = $item->purchase_price;
                $wholesale_item->selling_price = $item->selling_price;
                $wholesale_item->category = $item->category;
                $wholesale_item->supplier = $item->supplier;
                $wholesale_item->photo = $item->photo;
                $wholesale_item->wholesale_id = $wholesale->id;
                $wholesale_item->save();
            }
            $this->clear_items();
        }

        return redirect('/wholesale');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wholesale  $wholesale
     * @return \Illuminate\Http\Response
     */
    public function show(Wholesale $wholesale)
    {
        Auth::user()->hasRole(['manager']);

        return view('pages.wholesale.show_wholesale', compact('wholesale'));
    }


    // AJAX
    /**
     * Get wholesale items.
     */
    public function get_item()
    {
        $items = session()->get('wholesale_items') ?? array();

        return view('pages.wholesale.item_list_wholesale', compact('items'));
    }

    /**
     * Get wholesale item subtotal.
     */
    public function grand_total()
    {
        $items = session()->get('wholesale_items') ?? array();
        $total = 0;
        foreach ($items as $key => $item) {
            $total += $item->purchase_price * $item->quantity;
        }

        return $total;
    }

    /**
     * Add item to wholesale.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function add_item(Request $request)
    {
        $sparepart = new Sparepart();
        $sparepart->id = $request->id;
        $sparepart->name = $request->name;
        $sparepart->category = $request->category;
        $sparepart->supplier = $request->supplier;
        $sparepart->quantity = $request->quantity;
        $sparepart->unit = $request->unit;
        $sparepart->purchase_price = str_replace('.', '', substr($request->purchase_price, 3));
        $sparepart->selling_price = str_replace('.', '', substr($request->selling_price, 3));
        $sparepart->photo = $request->id . '_' . str_replace(' ', '-', str_replace('/', '-', strtolower($request->name))) . $request->ext;

        session()->has('wholesale_items') ? : session()->put('wholesale_items', []);
        session()->push('wholesale_items', $sparepart);
    }

    /**
     * Upload photo.
     */
    public function upload()
    {
        if($_FILES) {
            $tmp = $_FILES['photo']['tmp_name'];
            $filename = $_POST['id'] . '_' . str_replace(' ', '-', strtolower($_POST['name'])) . '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $destination = public_path("uploads\product\\" . $filename);

            if (move_uploaded_file($tmp, $destination)) return 1;
            else return 0;
        }
    }

    /**
     * Delete specific item from wholesale.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function delete_item(Request $request)
    {
        $items = session()->get('wholesale_items');
        foreach ($items as $key => $item) {
            if($key == $request->key) unset($items[$key]);
        }

        count($items) > 0 ? session()->put('wholesale_items', $items) : session()->forget('wholesale_items');
    }

    /**
     * Clear wholesale items.
     */
    public function clear_items()
    {
        session()->forget('wholesale_items');
    }
}
