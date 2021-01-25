<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendmailController extends Controller
{
    //
    public function sendMail($id)
    {
        dd($id);
        $user = User::find(1)->toArray();
        Mail::send('emails.mail', $user, function($message) use ($user) {
            $message->to('bboybourrik972@hotmail.com');
            $message->subject('Mailgun Testing');
        });
        dd('Mail Senddddddddddd Successfully');
    }
}
