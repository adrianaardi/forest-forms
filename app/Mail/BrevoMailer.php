<?php

namespace App\Mail;

use Illuminate\Support\Facades\Mail;

class BrevoMailer
{
    public static function send(string $to, string $toName, string $subject, string $htmlContent): bool
    {
        $sendWithMailer = function (string $mailer) use ($to, $toName, $subject, $htmlContent): bool {
            try {
                Mail::mailer($mailer)->html($htmlContent, function ($message) use ($to, $toName, $subject) {
                    $message->to($to, $toName)->subject($subject);
                });

                return true;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error(strtoupper($mailer) . ' mail error: ' . $e->getMessage());
                return false;
            }
        };

        $brevoHost = (string) config('mail.mailers.brevo.host');
        $brevoUser = (string) config('mail.mailers.brevo.username');
        $brevoPass = (string) config('mail.mailers.brevo.password');
        if ($brevoHost !== '' && $brevoUser !== '' && $brevoPass !== '' && $sendWithMailer('brevo')) {
            return true;
        }

        $smtpHost = (string) config('mail.mailers.smtp.host');
        $smtpUser = (string) config('mail.mailers.smtp.username');
        $smtpPass = (string) config('mail.mailers.smtp.password');
        if ($smtpHost !== '' && $smtpUser !== '' && $smtpPass !== '' && $sendWithMailer('smtp')) {
            return true;
        }

        $brevoToken = (string) config('services.brevo.smtp');
        if ($brevoToken === '') {
            \Illuminate\Support\Facades\Log::error('Brevo mail error: Missing BREVO_SMTP token and SMTP is not configured.');
            return false;
        }

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('https://api.brevo.com/v3/smtp/email', [
                'headers' => [
                    'api-key' => $brevoToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'sender' => [
                        'name' => config('mail.from.name'),
                        'email' => config('mail.from.address'),
                    ],
                    'to' => [['email' => $to, 'name' => $toName]],
                    'subject' => $subject,
                    'htmlContent' => $htmlContent,
                ],
            ]);

            return $response->getStatusCode() === 201;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Brevo mail error: ' . $e->getMessage());
            return false;
        }
    }
}