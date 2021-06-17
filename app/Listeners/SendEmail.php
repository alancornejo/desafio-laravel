<?php

namespace App\Listeners;

use App\Events\UserHasCreated;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserEmail;

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
        $params = ["name"=> $event->name, "email"=>$event->email];
        Mail::to($event->email)->queue(
            new UserEmail($params)
        );
    }
}
