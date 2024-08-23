<?php

namespace App\Mail\Tenant\Tenant;

use App\Mail\Tenant\TenantNotificationEmail;

class TenantDeletedEmail extends TenantNotificationEmail
{
    public function build()
    {
        $subject = "Your account has been deleted";

        return $this->view('mail.tenant.tenant.deleted')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
