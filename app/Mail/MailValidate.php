<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EcommRegister;

class MailValidate extends Mailable
{
    use Queueable, SerializesModels;

    public $info_user = [];

    public function __construct($user)
    {
        $this->info_user = $user;
    }

    public function build()
    {
        ;
        return $this->subject('Lifeprosper | Replice Password')->view('ecomm.layouts.ecomm_body_mail');
    }
}
