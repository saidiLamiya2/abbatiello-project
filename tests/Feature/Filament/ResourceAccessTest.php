<?php

use App\Enums\UserRole;
use App\Models\Brand;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed roles
    foreach (UserRole::cases() as $role) {
        Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
    }

    // Seed permissions
    $permissions = [
        'ViewAny:Brand', 'View:Brand', 'Create:Brand', 'Update:Brand', 'Delete:Brand',
        'ViewAny:Store', 'View:Store', 'Create:Store', 'Update:Store', 'Delete:Store',
        'ViewAny:User',  'View:User',  'Create:User',  'Update:User',  'Delete:User',
        'ViewAny:Theme', 'View:Theme', 'Create:Theme', 'Update:Theme', 'Delete:Theme',
    ];
    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }

    // Assign permissions to roles
    Role::findByName('admin')->syncPermissions([
        'ViewAny:Brand', 'View:Brand', 'Create:Brand', 'Update:Brand', 'Delete:Brand',
        'ViewAny:Store', 'View:Store', 'Create:Store', 'Update:Store', 'Delete:Store',
        'ViewAny:User',  'View:User',  'Create:User',  'Update:User',  'Delete:User',
    ]);

    Role::findByName('manager')->syncPermissions([
        'ViewAny:Store', 'View:Store', 'Update:Store',
        'ViewAny:User',  'View:User',  'Create:User',  'Update:User',  'Delete:User',
    ]);
});

// ── Helpers ───────────────────────────────────────────────────────────────────

function makeBrand(): Brand
{
    return Brand::create([
        'name' => fake()->company(),
        'tag'  => strtoupper(fake()->lexify('???')),
    ]);
}

function makeStore(Brand $brand): Store
{
    return Store::create([
        'brand_id' => $brand->id,
        'name'     => fake()->company(),
        'city'     => fake()->city(),
        'slug'     => fake()->slug(),
    ]);
}

function makeUser(UserRole $role, ?Brand $brand = null, ?Store $store = null): User
{
    $user = User::factory()->create([
        'is_active' => true,
        'brand_id'  => $brand?->id,
        'store_id'  => $store?->id,
    ]);
    $user->assignRole($role->value);
    return $user;
}

// ── Brand resource access ─────────────────────────────────────────────────────

it('super_admin can access brands resource', function () {
    $user = makeUser(UserRole::SuperAdmin);

    $this->actingAs($user)->get('/admin/brands')->assertOk();
});

it('admin can access brands resource', function () {
    $brand = makeBrand();
    $user  = makeUser(UserRole::Admin, $brand);

    $this->actingAs($user)->get('/admin/brands')->assertOk();
});

it('manager cannot access brands resource', function () {
    $brand = makeBrand();
    $store = makeStore($brand);
    $user  = makeUser(UserRole::Manager, $brand, $store);

    $this->actingAs($user)->get('/admin/brands')->assertForbidden();
});

it('employee cannot access brands resource', function () {
    $user = makeUser(UserRole::Employee);

    $this->actingAs($user)->get('/admin/brands')->assertForbidden();
});

// ── Theme resource access ─────────────────────────────────────────────────────

it('super_admin can access themes resource', function () {
    $user = makeUser(UserRole::SuperAdmin);

    $this->actingAs($user)->get('/admin/themes')->assertOk();
});

it('admin cannot access themes resource', function () {
    $brand = makeBrand();
    $user  = makeUser(UserRole::Admin, $brand);

    $this->actingAs($user)->get('/admin/themes')->assertForbidden();
});

// ── Store resource access ─────────────────────────────────────────────────────

it('manager can access stores resource', function () {
    $brand = makeBrand();
    $store = makeStore($brand);
    $user  = makeUser(UserRole::Manager, $brand, $store);

    $this->actingAs($user)->get('/admin/stores')->assertOk();
});

it('employee cannot access stores resource', function () {
    $user = makeUser(UserRole::Employee);

    $this->actingAs($user)->get('/admin/stores')->assertForbidden();
});