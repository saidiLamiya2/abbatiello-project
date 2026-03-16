<?php

use App\Enums\ProjectType;
use App\Enums\UserLocale;
use App\Enums\UserRole;
use Tests\TestCase;

uses(TestCase::class);

// ── ProjectType ───────────────────────────────────────────────────────────────

it('has all expected project type cases', function () {
    expect(ProjectType::cases())->toHaveCount(4);
});

it('returns correct color for each project type', function () {
    expect(ProjectType::Nouveau->color())->toBe('success')
        ->and(ProjectType::Corpo->color())->toBe('info')
        ->and(ProjectType::Reprise->color())->toBe('warning')
        ->and(ProjectType::Vente->color())->toBe('danger');
});

it('returns options array with all cases', function () {
    expect(ProjectType::options())->toHaveCount(4)
        ->toHaveKeys(['Nouveau', 'Corpo', 'Reprise', 'Vente']);
});

it('can be created from valid string value', function () {
    expect(ProjectType::tryFrom('Nouveau'))->toBe(ProjectType::Nouveau);
});

it('returns null for invalid project type value', function () {
    expect(ProjectType::tryFrom('franchise'))->toBeNull();
});

// ── UserRole ──────────────────────────────────────────────────────────────────

it('has all expected role cases', function () {
    expect(UserRole::cases())->toHaveCount(4);
});

it('returns correct color for each role', function () {
    expect(UserRole::SuperAdmin->color())->toBe('danger')
        ->and(UserRole::Admin->color())->toBe('warning')
        ->and(UserRole::Manager->color())->toBe('info')
        ->and(UserRole::Employee->color())->toBe('gray');
});

it('excludes specified roles from options', function () {
    $options = UserRole::optionsExcluding(UserRole::SuperAdmin);

    expect($options)
        ->not->toHaveKey(UserRole::SuperAdmin->value)
        ->toHaveCount(3);
});

it('excludes multiple roles from options', function () {
    $options = UserRole::optionsExcluding(UserRole::SuperAdmin, UserRole::Admin);

    expect($options)->toHaveCount(2)
        ->not->toHaveKey(UserRole::SuperAdmin->value)
        ->not->toHaveKey(UserRole::Admin->value);
});

// ── UserLocale ────────────────────────────────────────────────────────────────

it('has french and english cases', function () {
    expect(UserLocale::cases())->toHaveCount(2);
});

it('returns correct labels', function () {
    expect(UserLocale::French->label())->toBe('Français')
        ->and(UserLocale::English->label())->toBe('English');
});

it('returns null for invalid locale', function () {
    expect(UserLocale::tryFrom('de'))->toBeNull();
});