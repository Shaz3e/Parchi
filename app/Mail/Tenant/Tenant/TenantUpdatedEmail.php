<?php

namespace App\Mail\Tenant\Tenant;

use App\Mail\Tenant\TenantNotificationEmail;

class TenantUpdatedEmail extends TenantNotificationEmail
{
    public function build()
    {
        $subject = "Your information has been updated";

        return $this->view('mail.tenant.tenant.updated')
            ->subject($subject)
            ->with([
                'subject' => $subject,
                'mailData' => $this->mailData
            ]);
    }
}
