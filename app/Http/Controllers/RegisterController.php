<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

use App\Models\User;
use App\Mail\Signup as SignupMail;

class RegisterController extends Controller
{
    public function create()
    {
        return view('signup');
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required|max:255',
            'username' => 'required|min:3|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|max:255'
        ]);
        
        $user = User::create($attributes);

        auth()->login($user);

        event(new Registered($user));
        
        return redirect('/email/verify')->with('success','Your account has been created successfully!');
    }
}
