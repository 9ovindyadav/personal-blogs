<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class Signup extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public User $user
    )
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $appName = env('APP_NAME');
        return $this
                ->subject("Welcome {$this->user->name} to {$appName}")
                ->view('emails.user.signup')
                ->attach(public_path('images/logo.png'),[
                    'as' => "{$appName}.png",
                    'mime' => 'image/png'
                ]);
    }
}
