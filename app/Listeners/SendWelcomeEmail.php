<?php

namespace App\Listeners;

use App\Mail\WelcomeCustomerMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if (! $user->is_admin) {
            Mail::to($user->email)->send(new WelcomeCustomerMail($user));
        }
    }
}
