<?php

namespace App\Mail;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BrevoMailer
{
    public static function send(string $to, string $toName, string $subject, string $htmlContent): bool
    {
        // use Laravel mail locally for Mailpit testing
        if (app()->environment('local')) {
            try {
                \Illuminate\Support\Facades\Mail::html($htmlContent, function($msg) use ($to, $toName, $subject) {
                    $msg->to($to, $toName)->subject($subject);
                });
                return true;
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Mail error: ' . $e->getMessage());
                return false;
            }
        }

        // use Brevo HTTP API in production
        $client = new \GuzzleHttp\Client();
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
            \Illuminate\Support\Facades\Log::error('Brevo mail error: ' . $e->getMessage());
            return false;
        }
    }
}