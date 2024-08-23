<?php

namespace App\Mail\Owner\Tenant;

use App\Mail\Owner\OwnerNotificationEmail;

class OwnerTenantDeletedEmail extends OwnerNotificationEmail
{
    public function build()
    {
        $subject = 'Notification: Tenant Deleted';

        return $this->view('mail.owner.tenant.deleted')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
