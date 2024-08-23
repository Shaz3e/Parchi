<?php

namespace App\Mail\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $mailData)
    {
        $this->mailData = $mailData;
    }

    public function build()
    {
        return $this->view('mail.tenant.notification')
            ->subject('Tenant Notification')
            ->with('mailData', $this->mailData);
    }
}
