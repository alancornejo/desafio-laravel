<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserHasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $email;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        Log::debug('Constructor  UserHasCreated');
        Log::debug('name: '. $data['name']);
        Log::debug('email: '. $data['email']);

        $this->name = $data['name'];
        $this->email = $data['email'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
