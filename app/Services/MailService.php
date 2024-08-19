<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendEmail($mailable, $recipient)
    {
        try {
            Mail::mailer('smtp')
                ->to($recipient)
                ->send($mailable);

            Log::info("Email sent with Primary SMTP Server.");
        } catch (Exception $e) {
            // Handle exception
            return response()->json(['message' => $e->getMessage()]);

            try {
                Mail::mailer('backup_smtp')
                    ->to($recipient)
                    ->send($mailable);

                Log::info("Email sent with Backup SMTP Server.");
            } catch (Exception $e) {
                Log::info("Both SMTP Server Failed to Send Email.");

                // Handle exception
                return response()->json(['message' => $e->getMessage()]);
            }
        }
    }
}
