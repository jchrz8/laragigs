<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\User;

class UserController extends Controller
{
    //Show register/create
    public function create(){
        return view('users.register');
    }

    //Create New user
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email',Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6'
        ]);
        //Hash Password
        $formFields['password'] = bcrypt($formFields['password']);
        //Crete user
        $user = User::create($formFields);
        //Login
        auth()->login($user);
        return redirect('/')->with('message','User created an dogged in');
    }
    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('message','you have been logout');
    }
    //login form
    public function login(){
        return view('users.login');
    }
    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        if(auth()->attempt($formFields)){
            $request->session()->regenerate();

            return redirect('/')->with('message','You have logged in');
        }
        return back()->withErrors(['email'=>'Invalid Credentials'])->onlyInput('email');
    }
}
