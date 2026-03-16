<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (UserRole::cases() as $role) {
        Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
    }
});

// ── Redirects ─────────────────────────────────────────────────────────────────

it('redirects root to admin login', function () {
    $this->get('/')->assertRedirect('/admin/login');
});

// ── Login via Fortify ─────────────────────────────────────────────────────────

it('allows active user to log in', function () {
    $user = User::factory()->create([
        'is_active' => true,
        'password'  => bcrypt('password'),
    ]);

    $this->post(route('login.store'), [
        'email'    => $user->email,
        'password' => 'password',
    ])->assertRedirect();

    $this->assertAuthenticatedAs($user);
});

it('logs in inactive user but panel blocks access', function () {
    $user = User::factory()->create([
        'is_active' => false,
        'password'  => bcrypt('password'),
    ]);

    $this->post(route('login.store'), [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    // Fortify authenticates successfully — is_active is enforced at panel level
    $this->assertAuthenticatedAs($user);

    // Panel should deny access
    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('rejects wrong password', function () {
    $user = User::factory()->create([
        'is_active' => true,
        'password'  => bcrypt('password'),
    ]);

    $this->post(route('login.store'), [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});