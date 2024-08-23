<?php

namespace App\Mail\Owner;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OwnerNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $mailData)
    {
        $this->mailData = $mailData;
    }

    public function build()
    {
        return $this->view('mail.owner.notification')
            ->subject('System Notification')
            ->with('mailData', $this->mailData);
    }
}
