<?php

namespace App\Mail\Tenant\Tenant;

use App\Mail\Tenant\TenantNotificationEmail;

class TenantDomainAddedEmail extends TenantNotificationEmail
{
    public function build()
    {
        $subject = "New Domain has been added";

        return $this->view('mail.tenant.tenant.domain-added')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
