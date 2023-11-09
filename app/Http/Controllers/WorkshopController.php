<?php

namespace App\Http\Controllers;

use App\Workshop;
use Auth;
use Illuminate\Http\Request;

class WorkshopController extends Controller
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

        $workshops = Workshop::all();

        return view('pages.workshop.index_workshop', compact('workshops'));
    }
}
