<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('hanya admin bisa akses route admin', function () {
    // Buat user biasa
    $user = User::factory()->create(['role' => 'user']);

    // Buat admin
    $admin = User::factory()->create(['role' => 'admin']);

    // User biasa tidak bisa akses route admin
    $response = $this->actingAs($user)->get('/admin/buses');
    $response->assertStatus(403); // Forbidden

    $response = $this->actingAs($user)->get('/admin/routes');
    $response->assertStatus(403);

    $response = $this->actingAs($user)->get('/admin/trips');
    $response->assertStatus(403);

    $response = $this->actingAs($user)->get('/admin/bookings');
    $response->assertStatus(403);

    // Admin bisa akses semua route admin
    $response = $this->actingAs($admin)->get('/admin/buses');
    $response->assertStatus(200);

    $response = $this->actingAs($admin)->get('/admin/routes');
    $response->assertStatus(200);

    $response = $this->actingAs($admin)->get('/admin/trips');
    $response->assertStatus(200);

    $response = $this->actingAs($admin)->get('/admin/bookings');
    $response->assertStatus(200);
});

test('user tidak terautentikasi tidak bisa akses route admin', function () {
    $response = $this->get('/admin/buses');
    $response->assertRedirect('/login');

    $response = $this->get('/admin/bookings');
    $response->assertRedirect('/login');
});

test('admin bisa akses dashboard admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/dashboard');
    $response->assertStatus(200);
});
