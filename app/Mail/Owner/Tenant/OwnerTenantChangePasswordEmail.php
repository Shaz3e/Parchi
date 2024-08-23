<?php

namespace App\Mail\Owner\Tenant;

use App\Mail\Owner\OwnerNotificationEmail;

class OwnerTenantChangePasswordEmail extends OwnerNotificationEmail
{
    public function build()
    {
        $subject = 'Notification: Tenant Password Changed';

        return $this->view('mail.owner.tenant.change-password')
        ->subject($subject)
        ->with([
            'subject' => $subject,
            'mailData' => $this->mailData
        ]);
    }
}
