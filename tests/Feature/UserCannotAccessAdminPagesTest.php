<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCannotAccessAdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_admin_pages()
    {
        // Membuat user biasa (bukan admin)
        $user = User::factory()->create([
            'role' => 'user'
        ]);

        // Login sebagai user biasa
        $this->actingAs($user);

        // Mencoba mengakses halaman admin
        $response = $this->get('/admin/buses');

        // Harus redirect ke 403 (forbidden) atau ke halaman login
        $response->assertStatus(403);

        $response = $this->get('/admin/routes');
        $response->assertStatus(403);

        $response = $this->get('/admin/trips');
        $response->assertStatus(403);

        $response = $this->get('/admin/bookings');
        $response->assertStatus(403);
    }
}
