<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplayPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_display_page_is_accessible(): void
    {
        $response = $this->get('/display/pergerakan');

        $response->assertStatus(200);
        $response->assertSee('Pergerakan Pegawai');
    }
}
