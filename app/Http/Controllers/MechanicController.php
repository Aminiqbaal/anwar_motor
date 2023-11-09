<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Mechanic;
use App\Salary;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MechanicController extends Controller
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

        $mechanics = User::where('role', 'mechanic')->where('workshop_id', Auth::user()->workshop_id)->get();

        return view('pages.mechanic.index_mechanic', compact('mechanics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.mechanic.create_mechanic');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_mechanic(Request $request)
    {
        Auth::user()->hasRole(['manager']);

        $request->validate([
            'name'         => 'required|unique:mechanics,name',
            'worked_since' => 'required|numeric|gt:0',
            'age'          => 'required|numeric|gt:0',
            'address'      => 'required'
        ]);

        $user = new User();
        $user->username = str_replace(' ', '', strtolower($request->name));
        $user->password = 'a';
        $user->role = 'mechanic';
        $user->workshop_id = Auth::user()->workshop_id;
        $user->save();

        $data = new Mechanic();
        $data->name = ucwords($request->name);
        $data->worked_since = $request->worked_since;
        $data->age = $request->age;
        $data->address = ucwords($request->address);
        $data->user_id = $user->id;

        if($data->save()) session()->flash('toast', ['success', 'Mekanik berhasil ditambahkan']);
        else session()->flash('toast', ['error', 'Mekanik gagal ditambahkan']);

        return redirect('/mechanic');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Auth::user()->hasRole(['manager']);
        $user->workshop_id == Auth::user()->workshop_id ? : abort('403');

        $salaries_group = array();
        $salaries = Salary::where('user_id', $user->id)->get();
        foreach ($salaries as $salary) {
            $date = date_format(date_create($salary->transaction->created_at), 'M Y');
            if(!array_key_exists($date, $salaries_group))
                $salaries_group[$date] = 0;
            $salaries_group[$date] += $salary->cutted_salary ?? $salary->salary;
        }

        $chart_data = [];
        foreach ($salaries_group as $key => $salary) {
            $data = [
                'label' => $key,
                'y' => $salary
            ];
            array_push($chart_data, $data);
        }

        $user->name = $user->data->name;

        return json_encode([$chart_data, $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        Auth::user()->hasRole(['manager']);
        User::firstWhere('id', $user->id)->role == 'mechanic' && $user->workshop_id == Auth::user()->workshop_id ? : abort('403');

        return view('pages.mechanic.edit_mechanic', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update_mechanic(Request $request, User $user)
    {
        Auth::user()->hasRole(['manager']);
        User::firstWhere('id', $user->id)->role == 'mechanic' && $user->workshop_id == Auth::user()->workshop_id ? : abort('403');

        $request->validate([
            'name'         => ['required',Rule::unique('mechanics', 'name')->ignore($user->data, 'name')],
            'worked_since' => 'required|numeric|gt:0',
            'age'          => 'required|numeric|gt:0',
            'address'      => 'required'
        ]);

        $user->username = str_replace(' ', '', strtolower($request->name));
        $user->save();

        $data = Mechanic::firstWhere('user_id', $user->id);
        $data->name = ucwords($request->name);
        $data->worked_since = $request->worked_since;
        $data->age = $request->age;
        $data->address = ucwords($request->address);

        if($data->save()) session()->flash('toast', ['success', 'Mekanik berhasil diubah']);
        else session()->flash('toast', ['error', 'Mekanik gagal diubah']);

        return redirect('/mechanic');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function delete_mechanic(User $user)
    {
        Auth::user()->hasRole(['manager']);
        User::firstWhere('id', $user->id)->role == 'mechanic' && $user->workshop_id == Auth::user()->workshop_id ? : abort('403');

        if($user->delete()) session()->flash('toast', ['success', 'Mekanik berhasil dihapus']);
        else session()->flash('toast', ['error', 'Mekanik gagal dihapus']);

        return redirect('/mechanic');
    }
}
