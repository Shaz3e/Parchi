<?php

namespace App\Mail\Tenant\Tenant;

use App\Mail\Tenant\TenantNotificationEmail;

class TenantChangePasswordEmail extends TenantNotificationEmail
{
    public function build()
    {
        $subject = 'Your Password Changed';

        return $this->view('mail.tenant.tenant.change-password')
        ->subject($subject)
        ->with([
            'subject' => $subject,
            'mailData' => $this->mailData
        ]);
    }
}
