<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $token)
    {
        $this->mailData = $mailData;
        $this->token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Link',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.auth.forget-password-email',
            with: [
                'mailData' => $this->mailData,
                'url' => config('app.domain') . '/api/v1/reset-password/' . $this->mailData->email . '/' . $this->token,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
