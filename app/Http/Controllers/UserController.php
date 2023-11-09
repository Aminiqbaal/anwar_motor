<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Auth::id() == $user->id && Auth::user()->role == 'mechanic' ? : abort('403');

        return view('pages.profile.show_profile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        Auth::id() == $user->id && Auth::user()->role == 'mechanic' ? : abort('403');

        return view('pages.profile.edit_profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update_user(Request $request, User $user)
    {
        Auth::id() == $user->id && Auth::user()->role == 'mechanic' ? : abort('403');

        $request->validate([
            'name' => 'required',
            'worked_since' => 'required|numeric',
            'age' => 'required|numeric',
            'address' => 'required',
        ]);

        if(!is_null($request->new_password)) {
            $request->validate([
                'new_password' => 'required',
                'old_password' => ['required', 'different:new_password', function($attribute, $value, $fail) use($user) {
                    if($value !== $user->password) $fail('The ' . $attribute . ' is wrong.');
                }]
            ]);
            $user->password = $request->new_password;
        }

        $user->data->name = $request->name;
        $user->data->worked_since = $request->worked_since;
        $user->data->age = $request->age;
        $user->data->address = $request->address;

        if($user->data->save()) session()->flash('toast', ['success', 'Profil berhasil diubah']);
        else session()->flash('toast', ['error', 'Profil gagal diubah']);

        return redirect('user/' . $user->id);
    }
}
