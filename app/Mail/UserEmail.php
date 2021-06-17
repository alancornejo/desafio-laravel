<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $message;

    public function __construct($data)
    {
        Log::debug('Constructor UserEmail', $data);
        $this->name = $data['name'];
        $this->email = $data['email'];
    }

    public function build()
    {
        return $this->markdown('emails.email_user')
            ->from('alancix@gmail.com', 'Usuario Laravel')
            ->subject('Creación de cuenta ');
    }
}