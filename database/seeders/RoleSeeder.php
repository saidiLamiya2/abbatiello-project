<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{

    private array $permissions = [
        // Brand
        'brand.viewAny',
        'brand.view',
        'brand.create',
        'brand.edit',
        'brand.delete',

        // Store
        'store.viewAny',
        'store.view',
        'store.create',
        'store.edit',
        'store.delete',

        // User
        'user.viewAny',
        'user.view',
        'user.create',
        'user.edit',
        'user.delete',
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Create all permissions ────────────────────────────────────────────
        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── super_admin ───────────────────────────────────────────────────────
        Role::firstOrCreate(['name' => 'super_admin']);

        // ── admin ─────────────────────────────────────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'brand.viewAny', 'brand.view', 'brand.create', 'brand.edit', 'brand.delete',
            'store.viewAny', 'store.view', 'store.create', 'store.edit', 'store.delete',
            'user.viewAny',  'user.view',  'user.create',  'user.edit',  'user.delete',
        ]);

        // ── manager ───────────────────────────────────────────────────────────
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'store.viewAny', 'store.view', 'store.edit',
            'user.viewAny',  'user.view',  'user.create', 'user.edit', 'user.delete',
        ]);

        // ── employee ──────────────────────────────────────────────────────────
        Role::firstOrCreate(['name' => 'employee']);

        $this->command->info('Roles and permissions seeded.');
        $this->command->line('  → super_admin : Gate::before() bypass (no permissions needed)');
        $this->command->line('  → admin       : full CRUD on brands, stores, users');
        $this->command->line('  → manager     : view/edit own store + manage own store users');
        $this->command->line('  → employee    : panel access only');
    }
}