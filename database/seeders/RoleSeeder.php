<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Permission naming convention: Action:Model
     * Actions:  ViewAny, View, Create, Update, Delete
     * Models:   Brand, Store, User, Theme
     */
    private array $permissions = [
        'ViewAny:Brand', 'View:Brand', 'Create:Brand', 'Update:Brand', 'Delete:Brand',
        'ViewAny:Store', 'View:Store', 'Create:Store', 'Update:Store', 'Delete:Store',
        'ViewAny:User',  'View:User',  'Create:User',  'Update:User',  'Delete:User',
        'ViewAny:Theme', 'View:Theme', 'Create:Theme', 'Update:Theme', 'Delete:Theme',
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // super_admin — Gate::before() bypass, no permissions needed
        Role::firstOrCreate(['name' => UserRole::SuperAdmin->value]);

        // admin — full CRUD scoped to own brand via Policy
        $admin = Role::firstOrCreate(['name' => UserRole::Admin->value]);
        $admin->syncPermissions([
            'ViewAny:Brand', 'View:Brand', 'Create:Brand', 'Update:Brand', 'Delete:Brand',
            'ViewAny:Store', 'View:Store', 'Create:Store', 'Update:Store', 'Delete:Store',
            'ViewAny:User',  'View:User',  'Create:User',  'Update:User',  'Delete:User',
        ]);

        // manager — view/edit own store + manage own store users via Policy
        $manager = Role::firstOrCreate(['name' => UserRole::Manager->value]);
        $manager->syncPermissions([
            'ViewAny:Store', 'View:Store', 'Update:Store',
            'ViewAny:User',  'View:User',  'Create:User',  'Update:User',  'Delete:User',
        ]);

        // employee — panel access only, no resource permissions
        Role::firstOrCreate(['name' => UserRole::Employee->value]);

        $this->command->info('Roles and permissions seeded.');
        $this->command->line('  → super_admin : Gate::before() bypass');
        $this->command->line('  → admin       : full CRUD on brands/stores/users (brand-scoped)');
        $this->command->line('  → manager     : view/edit own store + manage own store users');
        $this->command->line('  → employee    : panel access only');
    }
}