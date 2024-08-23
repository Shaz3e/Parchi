<?php

namespace App\Mail\Tenant\Tenant;

use App\Mail\Tenant\TenantNotificationEmail;

class TenantRegisterEmail extends TenantNotificationEmail
{
    public function build()
    {
        $subject = "Account Registration Email";

        return $this->view('mail.tenant.tenant.register')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
