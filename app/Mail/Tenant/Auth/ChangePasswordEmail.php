<?php

namespace App\Mail\Tenant\Auth;

use App\Mail\Tenant\TenantNotificationEmail;

class ChangePasswordEmail extends TenantNotificationEmail
{
    public function build()
    {
        $subject = 'Your Password has been changed successfully';

        return $this->view('mail.tenant.auth.change-password')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData,
            ]);
    }
}
