<?php

namespace App\Mail\Owner\Tenant;

use App\Mail\Owner\OwnerNotificationEmail;

class OwnerTenantDomainAddedEmail extends OwnerNotificationEmail
{
    public function build()
    {
        $subject = 'Notification: New Domain Added to Tenant';

        return $this->view('mail.owner.tenant.domain-added')
        ->subject($subject)
        ->with([
            'subject' => $subject,
            'mailData' => $this->mailData
        ]);
    }
}
