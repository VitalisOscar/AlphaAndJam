<?php

namespace App\Services;

use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailService{
    function send(Mailable $mail){
        if(config('mail.fake'))
        Mail::fake()->send($mail);
        else
        Mail::send($mail);
    }
}
