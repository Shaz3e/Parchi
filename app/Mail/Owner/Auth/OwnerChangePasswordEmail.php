<?php

namespace App\Mail\Owner\Auth;

use App\Mail\Owner\OwnerNotificationEmail;

class OwnerChangePasswordEmail extends OwnerNotificationEmail
{
    public function build()
    {
        $subject = 'Your Password has been changed successfully';

        return $this->view('mail.owner.auth.change-password')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData,
            ]);
    }
}
