<?php

namespace App\Mail\Tenant\Auth;

use App\Mail\Tenant\TenantNotificationEmail;

class ForgotPasswordEmail extends TenantNotificationEmail
{
    public function build()
    {
        $subject = '';

        return $this->view('mail.tenant.auth.forgot-password')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
