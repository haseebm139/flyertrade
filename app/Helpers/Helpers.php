<?php
if (!function_exists('sendVerificationMail')) {
    function sendVerificationMail($otp,$email)
    {
        dd($email);
        // Send Email
            Illuminate\Support\Facades\Mail::send('emails.reset-password-email', ['otp' => $otp], function($message) use($email){
                $message->to($email, 'Verification Code From FlyerTrader');
                $message->subject('You have received Verification Code');
            });
        // Send Email
    }
}
