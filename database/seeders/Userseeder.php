<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $sal         = Brand::where('tag', 'SAL-')->firstOrFail();
        $ccm         = Brand::where('tag', 'CCM')->firstOrFail();
        $salEvent    = Store::where('slug', 'sal-event')->firstOrFail();
        $salLebourg  = Store::where('slug', 'sal-lebourg')->firstOrFail();
        $ccmAnselme  = Store::where('slug', 'ccmaint-Anselme')->firstOrFail();
        $ccmBeauport = Store::where('slug', 'CCM-BEAUPORT')->firstOrFail();

        $users = [

            // ── super_admin ──────────────────────────────────────────────────
            [
                'data' => [
                    'brand_id'   => null,
                    'store_id'   => null,
                    'first_name' => 'Super',
                    'last_name'  => 'Admin',
                    'email'      => 'superadmin@platform.test',
                    'password'   => Hash::make('password'),
                    'user_code'  => null,
                    'is_active'  => true,
                    'locale'     => 'fr',
                ],
                'role' => 'super_admin',
            ],

            // ── admin — Salvatoré ────────────────────────────────────────────
            [
                'data' => [
                    'brand_id'   => $sal->id,
                    'store_id'   => null,
                    'first_name' => 'Marco',
                    'last_name'  => 'Abbatiello',
                    'email'      => 'marco@groupeabbatiello.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'SAL-A001',
                    'is_active'  => true,
                    'hired_at'   => '2018-01-01',
                    'birth_date' => '1975-06-20',
                    'locale'     => 'fr',
                ],
                'role' => 'admin',
            ],

            // ── admin — Crèmerie Chez Mamie ──────────────────────────────────
            [
                'data' => [
                    'brand_id'   => $ccm->id,
                    'store_id'   => null,
                    'first_name' => 'Sophie',
                    'last_name'  => 'Abbatiello',
                    'email'      => 'sophie@groupeabbatiello.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'CCM-A001',
                    'is_active'  => true,
                    'hired_at'   => '2019-05-01',
                    'birth_date' => '1980-03-12',
                    'locale'     => 'fr',
                ],
                'role' => 'admin',
            ],

            // ── manager — Salvatoré Évènementiel ────────────────────────────
            [
                'data' => [
                    'brand_id'   => $sal->id,
                    'store_id'   => $salEvent->id,
                    'first_name' => 'Charles',
                    'last_name'  => 'Jobin',
                    'email'      => 'c.jobin@operationfranchises.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'SAL-M001',
                    'is_active'  => true,
                    'hired_at'   => '2018-06-01',
                    'birth_date' => '1982-09-14',
                    'locale'     => 'fr',
                ],
                'role' => 'manager',
            ],

            // ── manager — SAL-Lebourgneuf ────────────────────────────────────
            [
                'data' => [
                    'brand_id'   => $sal->id,
                    'store_id'   => $salLebourg->id,
                    'first_name' => 'Marie',
                    'last_name'  => 'Tremblay',
                    'email'      => 'marie.tremblay@groupeabbatiello.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'SAL-M002',
                    'is_active'  => true,
                    'hired_at'   => '2021-09-01',
                    'birth_date' => '1985-04-22',
                    'locale'     => 'fr',
                ],
                'role' => 'manager',
            ],

            // ── manager — CCM Saint-Anselme ──────────────────────────────────
            [
                'data' => [
                    'brand_id'   => $ccm->id,
                    'store_id'   => $ccmAnselme->id,
                    'first_name' => 'Jonathan',
                    'last_name'  => 'Leblanc',
                    'email'      => 'jonathan.leblanc@groupeabbatiello.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'CCM-M001',
                    'is_active'  => true,
                    'hired_at'   => '2019-06-15',
                    'birth_date' => '1978-11-30',
                    'locale'     => 'fr',
                ],
                'role' => 'manager',
            ],

            // ── manager — CCM Beauport ───────────────────────────────────────
            [
                'data' => [
                    'brand_id'   => $ccm->id,
                    'store_id'   => $ccmBeauport->id,
                    'first_name' => 'Kevin',
                    'last_name'  => 'Bouchard',
                    'email'      => 'beauport@cremeriechezmamie.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'CCM-M002',
                    'is_active'  => true,
                    'hired_at'   => '2022-07-01',
                    'birth_date' => '1983-02-08',
                    'locale'     => 'fr',
                ],
                'role' => 'manager',
            ],

            // ── employee — active (SAL Évènementiel) ────────────────────────
            [
                'data' => [
                    'brand_id'   => $sal->id,
                    'store_id'   => $salEvent->id,
                    'first_name' => 'Sarah',
                    'last_name'  => 'Roy',
                    'email'      => 'sarah.roy@groupeabbatiello.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'SAL-E001',
                    'is_active'  => true,
                    'hired_at'   => '2020-05-01',
                    'birth_date' => '1999-03-15',
                    'locale'     => 'fr',
                ],
                'role' => 'employee',
            ],

            // ── employee — work stoppage (SAL Évènementiel) ─────────────────
            [
                'data' => [
                    'brand_id'                 => $sal->id,
                    'store_id'                 => $salEvent->id,
                    'first_name'               => 'Luc',
                    'last_name'                => 'Gagnon',
                    'email'                    => 'luc.gagnon@groupeabbatiello.com',
                    'password'                 => Hash::make('password'),
                    'user_code'                => 'SAL-E002',
                    'is_active'                => true,
                    'hired_at'                 => '2019-11-01',
                    'is_work_stoppage'         => true,
                    'work_stoppage_start_date' => '2024-10-15',
                    'work_stoppage_end_date'   => null, // Still ongoing
                    'birth_date'               => '1993-08-30',
                    'locale'                   => 'fr',
                ],
                'role' => 'employee',
            ],

            // ── employee — terminated (SAL-Lebourgneuf) ─────────────────────
            [
                'data' => [
                    'brand_id'           => $sal->id,
                    'store_id'           => $salLebourg->id,
                    'first_name'         => 'Julie',
                    'last_name'          => 'Côté',
                    'email'              => 'julie.cote@groupeabbatiello.com',
                    'password'           => Hash::make('password'),
                    'user_code'          => 'SAL-E003',
                    'is_active'          => false,
                    'hired_at'           => '2021-09-15',
                    'terminated_at'      => '2023-12-31',
                    'termination_reason' => 'Démission volontaire',
                    'birth_date'         => '1997-05-20',
                    'locale'             => 'fr',
                ],
                'role' => 'employee',
            ],

            // ── employee — active (CCM Saint-Anselme) ───────────────────────
            [
                'data' => [
                    'brand_id'   => $ccm->id,
                    'store_id'   => $ccmAnselme->id,
                    'first_name' => 'Émilie',
                    'last_name'  => 'Bergeron',
                    'email'      => 'emilie.bergeron@groupeabbatiello.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'CCM-E001',
                    'is_active'  => true,
                    'hired_at'   => '2019-07-01',
                    'birth_date' => '2001-01-18',
                    'locale'     => 'fr',
                ],
                'role' => 'employee',
            ],

            // ── employee — active (CCM Beauport) ────────────────────────────
            [
                'data' => [
                    'brand_id'   => $ccm->id,
                    'store_id'   => $ccmBeauport->id,
                    'first_name' => 'Nicolas',
                    'last_name'  => 'Fortin',
                    'email'      => 'nicolas.fortin@groupeabbatiello.com',
                    'password'   => Hash::make('password'),
                    'user_code'  => 'CCM-E002',
                    'is_active'  => true,
                    'hired_at'   => '2022-07-15',
                    'birth_date' => '2000-06-11',
                    'locale'     => 'fr',
                ],
                'role' => 'employee',
            ],
        ];

        foreach ($users as $entry) {
            $user = User::firstOrCreate(
                ['email' => $entry['data']['email']],
                $entry['data']
            );

            $user->syncRoles([$entry['role']]);
        }

        $this->command->info('Users seeded (' . count($users) . ')');
        $this->command->line('  Login credentials (all): password');
        $this->command->line('  superadmin@platform.test              → super_admin');
        $this->command->line('  marco@groupeabbatiello.com            → admin    (SAL)');
        $this->command->line('  sophie@groupeabbatiello.com           → admin    (CCM)');
        $this->command->line('  c.jobin@operationfranchises.com       → manager  (SAL Évènementiel)');
        $this->command->line('  marie.tremblay@groupeabbatiello.com   → manager  (SAL-Lebourgneuf)');
        $this->command->line('  jonathan.leblanc@groupeabbatiello.com → manager  (CCM Saint-Anselme)');
        $this->command->line('  beauport@cremeriechezmamie.com        → manager  (CCM Beauport)');
        $this->command->line('  sarah.roy@groupeabbatiello.com        → employee (SAL Évènementiel, active)');
        $this->command->line('  luc.gagnon@groupeabbatiello.com       → employee (SAL Évènementiel, work stoppage)');
        $this->command->line('  julie.cote@groupeabbatiello.com       → employee (SAL-Lebourgneuf, terminated)');
        $this->command->line('  emilie.bergeron@groupeabbatiello.com  → employee (CCM Saint-Anselme, active)');
        $this->command->line('  nicolas.fortin@groupeabbatiello.com   → employee (CCM Beauport, active)');
    }
}