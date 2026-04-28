<?php

namespace App\Mail;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BrevoMailer
{
    public static function send(string $to, string $toName, string $subject, string $htmlContent): bool
    {
        $client = new Client();

        try {
            $response = $client->post('https://api.brevo.com/v3/smtp/email', [
                'headers' => [
                    'api-key'      => config('services.brevo.api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'sender'      => [
                        'name'  => config('mail.from.name'),
                        'email' => config('mail.from.address'),
                    ],
                    'to'          => [['email' => $to, 'name' => $toName]],
                    'subject'     => $subject,
                    'htmlContent' => $htmlContent,
                ],
            ]);

            return $response->getStatusCode() === 201;

        } catch (\Throwable $e) {
            Log::error('Brevo mail error: ' . $e->getMessage());
            return false;
        }
    }
}