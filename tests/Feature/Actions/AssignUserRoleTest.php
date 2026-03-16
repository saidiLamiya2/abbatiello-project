<?php

use App\Actions\Users\AssignUserRole;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed all roles so Spatie can find them
    foreach (UserRole::cases() as $role) {
        Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
    }

    $this->action = app(AssignUserRole::class);
});

it('assigns a role to a user', function () {
    $user = User::factory()->create();

    $this->action->execute($user, UserRole::Employee->value);

    expect($user->fresh()->hasRole(UserRole::Employee->value))->toBeTrue();
});

it('replaces an existing role with the new one', function () {
    $user = User::factory()->create();
    $user->assignRole(UserRole::Employee->value);

    $this->action->execute($user, UserRole::Manager->value);

    expect($user->fresh()->hasRole(UserRole::Manager->value))->toBeTrue()
        ->and($user->fresh()->hasRole(UserRole::Employee->value))->toBeFalse();
});

it('does not assign a role when role is null', function () {
    $user = User::factory()->create();

    $this->action->execute($user, null);

    expect($user->fresh()->roles)->toBeEmpty();
});

it('does not assign a role when role is empty string', function () {
    $user = User::factory()->create();

    $this->action->execute($user, '');

    expect($user->fresh()->roles)->toBeEmpty();
});

it('enforces one role at a time', function () {
    $user = User::factory()->create();
    $user->assignRole(UserRole::Admin->value);
    $user->assignRole(UserRole::Manager->value);

    $this->action->execute($user, UserRole::Employee->value);

    expect($user->fresh()->roles)->toHaveCount(1)
        ->and($user->fresh()->hasRole(UserRole::Employee->value))->toBeTrue();
});