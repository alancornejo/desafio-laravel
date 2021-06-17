<?php

namespace App\Listeners;

use App\Events\UserHasCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserEmail;
use Illuminate\Support\Facades\Log;

class SendEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  UserHasCreated  $event
     * @return void
     */
    public function handle(UserHasCreated $event)
    {
        Log::debug('Handler  Listerner SendEmail');
        Log::debug('name: '.$event->name);
        Log::debug('email: '.$event->email);
        $params = ["name"=> $event->name, "email"=>$event->email];
        Mail::to($event->email)->queue(
            new UserEmail($params)
        );
    }
}
