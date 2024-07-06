<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Jobs\ProcessEmails;
use App\Mail\Signup as SignupMail;
use App\Models\User;

class EmailVerifyController extends Controller
{
    public function index()
    {
        return view('auth.verify-email');
    }

    public function notify(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
    
        return back()->with('status', 'Verification link sent!');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        $user = $request->user();

        ProcessEmails::dispatch($user, new SignupMail($user))
            ->delay(now()->addMinutes(1));

        return redirect('/')->with('status','Email verification successful');
    }
}
