<?php

namespace App\Mail\Owner\Tenant;

use App\Mail\Owner\OwnerNotificationEmail;

class OwnerTenantUpdatedEmail extends OwnerNotificationEmail
{
    public function build()
    {
        $subject = 'Notification: Tenant Information Updated Created';

        return $this->view('mail.owner.tenant.updated')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
