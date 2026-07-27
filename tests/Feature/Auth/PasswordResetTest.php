<?php

namespace Tests\Feature\Auth;

use App\Mail\BrevoMailer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Mockery;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create();

        $mailer = Mockery::mock('alias:' . BrevoMailer::class);
        $mailer->shouldReceive('send')
            ->once()
            ->andReturn(true);

        $this->post('/forgot-password', ['email' => $user->email]);
    }

    public function test_auth_password_reset_notification_uses_brevo(): void
    {
        $user = User::factory()->create();
        $token = 'test-token';

        $mailer = Mockery::mock('alias:' . BrevoMailer::class);
        $mailer->shouldReceive('send')
            ->once()
            ->withArgs(function ($to, $toName, $subject, $htmlContent) use ($user) {
                $this->assertSame($user->email, $to);
                $this->assertSame($user->name, $toName);
                $this->assertStringContainsString('Reset', $subject);
                $this->assertStringContainsString('password', strtolower($htmlContent));

                return true;
            });

        $user->sendPasswordResetNotification($token);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->get('/reset-password/test-token?email=' . urlencode($user->email));

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));
    }
}
