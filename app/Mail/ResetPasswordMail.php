<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject("Reset Password OTP")
                    ->view('emails.reset_password')
                    ->with(['otp' => $this->otp]);
    }








    // public $token;
    // public $email;

    // /**
    //  * Create a new message instance.
    //  */
    // public function __construct($token, $email)
    // {
    //     $this->token = $token;
    //     $this->email = $email;
    // }

    // /**
    //  * Build the message.
    //  */
    // public function build()
    // {
    //     return $this->subject('Reset Password Token')
    //                 ->view('emails.reset_password')
    //                 ->with([
    //                     'token' => $this->token,
    //                     'email' => $this->email,
    //                 ]);
    // }
}
