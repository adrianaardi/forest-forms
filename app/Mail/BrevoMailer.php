<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoMailer
{
    public static function send(string $to, string $toName, string $subject, string $htmlContent): bool
    {
        $apiKey = (string) config('services.brevo.key');

        if ($apiKey === '') {
            Log::error('Brevo mail error: BREVO_API_KEY is not set.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => config('mail.from.name'),
                    'email' => config('mail.from.address'),
                ],
                'to' => [
                    ['email' => $to, 'name' => $toName],
                ],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Brevo API mail error: ' . $response->status() . ' ' . $response->body());
            return false;
        } catch (\Throwable $e) {
            Log::error('Brevo API mail exception: ' . $e->getMessage());
            return false;
        }
    }
}