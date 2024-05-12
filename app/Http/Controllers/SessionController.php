<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('login');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!auth()->attempt($attributes)){
            throw ValidationException::withMessages([
                'email' => 'Credentials can\'t be verified'
            ]);
 
        }

        session()->regenerate();
        
        return redirect('/')->with('success','Welcome back');
    }

    public function destroy()
    {
        auth()->logout();

        return redirect('/')->with('success','Logged out successfully');
    }
}
