<?php

use App\Enums\UserLocale;
use App\Models\User;
use App\Services\LocaleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(LocaleService::class);
});

// ── resolveFor() ──────────────────────────────────────────────────────────────

it('resolves the user locale when valid', function () {
    $user = User::factory()->make(['locale' => 'en']);

    expect($this->service->resolveFor($user))->toBe('en');
});

it('falls back to app default when user locale is invalid', function () {
    $user = User::factory()->make(['locale' => 'invalid']);

    expect($this->service->resolveFor($user))->toBe(config('app.locale'));
});

it('falls back to app default when user is null', function () {
    expect($this->service->resolveFor(null))->toBe(config('app.locale'));
});

// ── switchFor() ───────────────────────────────────────────────────────────────

it('updates the user locale in the database', function () {
    $user = User::factory()->create(['locale' => UserLocale::French->value]);

    $this->service->switchFor($user, UserLocale::English->value);

    expect($user->fresh()->locale)->toBe(UserLocale::English->value);
});

it('sets the app locale immediately', function () {
    $user = User::factory()->create(['locale' => UserLocale::French->value]);

    $this->service->switchFor($user, UserLocale::English->value);

    expect(App::getLocale())->toBe('en');
});

it('does not update locale when value is invalid', function () {
    $user = User::factory()->create(['locale' => UserLocale::French->value]);

    $this->service->switchFor($user, 'invalid');

    expect($user->fresh()->locale)->toBe(UserLocale::French->value);
});