<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class userMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $password;
    public $uuid;

    public function __construct($username, $password,$uuid)
    {
        $this->username = $username;
        $this->password = $password;
        $this->uuid = $uuid;
    }

    public function build()
    {
        return $this->subject('Informasi Akun KasirApp')
                    ->view('emails.user-account');
    }
}
