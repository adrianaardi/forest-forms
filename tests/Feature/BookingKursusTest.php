<?php

namespace Tests\Feature;

use App\Models\BookingKursus;
use App\Models\BookingKursusApplication;
use App\Models\BookingUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingKursusTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_is_publicly_accessible(): void
    {
        $response = $this->get('/booking/katalog-kursus');

        $response->assertOk();
        $response->assertSee('Katalog Kursus');
    }

    public function test_only_can_book_user_can_create_course(): void
    {
        $user = BookingUser::create([
            'name' => 'Pemohon Biasa',
            'email' => 'biasa@example.com',
            'password' => Hash::make('password123'),
            'status' => 'approved',
            'can_book' => false,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user, 'booking_user')->get('/booking/katalog-kursus/tambah');

        $response->assertForbidden();
    }

    public function test_can_book_user_can_add_course(): void
    {
        $user = BookingUser::create([
            'name' => 'Penyelia Kursus',
            'email' => 'penyelia@example.com',
            'password' => Hash::make('password123'),
            'status' => 'approved',
            'can_book' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user, 'booking_user')->post('/booking/katalog-kursus', [
            'tajuk' => 'Pengurusan Projek Sektor Awam',
            'penganjur' => 'Institut Perkhidmatan Awam Sarawak',
            'ringkasan' => 'Latihan asas pengurusan projek untuk pegawai jabatan.',
            'lokasi' => 'Wisrma Bapa Malaysia, Kuching',
            'tarikh_mula' => '2026-09-08',
            'tarikh_tamat' => '2026-09-10',
            'jumlah_tempat' => 30,
            'yuran' => 450,
            'is_dalam_sarawak' => 1,
        ]);

        $response->assertRedirect('/booking/katalog-kursus');
        $this->assertDatabaseHas('booking_kursuses', [
            'tajuk' => 'Pengurusan Projek Sektor Awam',
            'created_by' => $user->id,
        ]);
    }

    public function test_registered_booking_user_can_apply_for_course(): void
    {
        $creator = BookingUser::create([
            'name' => 'Penyelaras',
            'email' => 'penyelaras@example.com',
            'password' => Hash::make('password123'),
            'status' => 'approved',
            'can_book' => true,
            'email_verified_at' => now(),
        ]);

        $applicant = BookingUser::create([
            'name' => 'Pegawai Permohonan',
            'email' => 'pegawai@example.com',
            'password' => Hash::make('password123'),
            'status' => 'approved',
            'can_book' => false,
            'email_verified_at' => now(),
        ]);

        $kursus = BookingKursus::create([
            'tajuk' => 'Analitik Data untuk Pelaporan Kerajaan',
            'penganjur' => 'Kampus Digital, Miri',
            'ringkasan' => 'Pendedahan kepada analitik data dan papan pemuka pelaporan.',
            'lokasi' => 'Kampung Digital, Miri',
            'tarikh_mula' => '2026-09-23',
            'tarikh_tamat' => '2026-09-24',
            'jumlah_tempat' => 25,
            'yuran' => 620,
            'is_dalam_sarawak' => true,
            'created_by' => $creator->id,
        ]);

        $response = $this->actingAs($applicant, 'booking_user')
            ->post('/booking/katalog-kursus/' . $kursus->id . '/mohon');

        $response->assertRedirect('/booking/katalog-kursus');
        $this->assertDatabaseHas('booking_kursus_applications', [
            'kursus_id' => $kursus->id,
            'booking_user_id' => $applicant->id,
            'status' => 'pending',
        ]);
        $this->assertSame(1, BookingKursusApplication::count());
    }
}