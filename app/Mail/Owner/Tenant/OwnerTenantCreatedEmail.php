<?php

namespace App\Mail\Owner\Tenant;

use App\Mail\Owner\OwnerNotificationEmail;

class OwnerTenantCreatedEmail extends OwnerNotificationEmail
{
    public function build()
    {
        $subject = 'Notification: New Tenant Created';

        return $this->view('mail.owner.tenant.created')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
