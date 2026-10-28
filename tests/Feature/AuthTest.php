<?php

use App\Models\User;

test('registration page can be rendered', function () {
    $response = $this->get('/register');
    $response->assertStatus(200);
});

test('citizen can register successfully with default role user', function () {
    $response = $this->post('/register', [
        'nama' => 'Ahmad Warga',
        'email' => 'ahmad@example.com',
        'no_hp' => '081298765432',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $user = User::where('email', 'ahmad@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe('user');
    expect($user->status)->toBe('aktif');
    $response->assertRedirect(route('user.dashboard'));
});

test('login page can be rendered', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

test('user can authenticate and gets redirected to user dashboard', function () {
    $user = User::create([
        'nama' => 'User Biasa',
        'email' => 'user@example.com',
        'no_hp' => '0811111111',
        'password' => bcrypt('password'),
        'role' => 'user',
        'status' => 'aktif',
    ]);

    $response = $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('user.dashboard'));
});

test('admin can authenticate and gets redirected to admin dashboard', function () {
    $admin = User::create([
        'nama' => 'Admin Dispora',
        'email' => 'admin@example.com',
        'no_hp' => '0822222222',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'status' => 'aktif',
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('admin.dashboard'));
});

test('superadmin can authenticate and gets redirected to superadmin dashboard', function () {
    $superadmin = User::create([
        'nama' => 'Super Admin',
        'email' => 'superadmin@example.com',
        'no_hp' => '0833333333',
        'password' => bcrypt('password'),
        'role' => 'superadmin',
        'status' => 'aktif',
    ]);

    $response = $this->post('/login', [
        'email' => 'superadmin@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($superadmin);
    $response->assertRedirect(route('superadmin.dashboard'));
});

test('inactive account cannot login', function () {
    $inactive = User::create([
        'nama' => 'Akun Dinonaktifkan',
        'email' => 'nonaktif@example.com',
        'no_hp' => '0844444444',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'status' => 'nonaktif',
    ]);

    $response = $this->post('/login', [
        'email' => 'nonaktif@example.com',
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});
