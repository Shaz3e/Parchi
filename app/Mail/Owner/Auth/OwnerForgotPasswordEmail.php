<?php

namespace App\Mail\Owner\Auth;

use App\Mail\Owner\OwnerNotificationEmail;

class OwnerForgotPasswordEmail extends OwnerNotificationEmail
{
    public function build()
    {
        $subject = 'Password Reset Link';

        return $this->view('mail.owner.auth.forgot-password')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData,
            ]);
    }
}
