<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookingLoginErrorTest extends TestCase
{
    public function test_calendar_login_modal_reveals_error_messages(): void
    {
        $view = view('booking._login-modal')->render();

        $this->assertStringContainsString('login-error', $view);
        $this->assertStringContainsString("errorEl.classList.remove('is-hidden');", view('booking.calendar')->render());
        $this->assertStringContainsString("errorEl.classList.add('is-hidden');", view('booking.calendar')->render());
    }
}
