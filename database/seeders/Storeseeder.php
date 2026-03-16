<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $sal = Brand::where('tag', 'SAL-')->firstOrFail();
        $ccm = Brand::where('tag', 'CCM')->firstOrFail();

        // ── Salvatoré ─────────────────────────────────────────────────────────

        // Root / corporate store
        $salHQ = Store::firstOrCreate(['slug' => 'sal-event'], [
            'brand_id'              => $sal->id,
            'name'                  => 'Salvatoré Evènementiel',
            'franchise_number'      => 'SAL-001',
            'city'                  => 'Québec',
            'province'              => 'QC',
            'address'               => '980 rue bouvier',
            'postal_code'           => 'G2J 1A2.',
            'phone'                 => '(866) 695-6666',
            'email'                 => 'pizzasalvatoreevenementiel@gmail.com',
            'is_active'             => true,
            'project_type'          => 'Corpo',
            'start_date'            => '2018-01-01',
            'expected_opening_date' => '2018-06-01',
        ]);

        // Child store
        Store::firstOrCreate(['slug' => 'sal-lebourg'], [
            'brand_id'              => $sal->id,
            'core_store_id'         => $salHQ->id,
            'name'                  => 'SAL-Lebourgneuf',
            'franchise_number'      => 'SAL-002',
            'city'                  => 'Québec',
            'province'              => 'QC',
            'address'               => '980 Rue Bouvier, Québec, QC G2J 1A3, Canada',
            'postal_code'           => 'G2J 1A3',
            'phone'                 => '(866) 695-6666',
            'email'                 => 'c.jobin@operationfranchises.com',
            'is_active'             => true,
            'project_type'          => 'Nouveau',
            'start_date'            => '2021-03-01',
            'expected_opening_date' => '2021-09-01',
        ]);

        // Inactive store — Reprise in progress
        Store::firstOrCreate(['slug' => 'sal-sainte-foy'], [
            'brand_id'         => $sal->id,
            'name'             => 'SAL-Sainte-Foy',
            'franchise_number' => 'SAL-003',
            'city'             => 'Québec',
            'province'         => 'QC',
            'address'          => '2377 Ch Ste-Foy, Québec, QC G1V 4H2, Canada',
            'postal_code'      => 'G1V 4H2',
            'phone'            => '(418) 658-8888',
            'is_active'        => false,
            'project_type'     => 'Reprise',
            'start_date'       => '2023-06-01',
        ]);

        // ── Crèmerie Chez Mamie ───────────────────────────────────────────────

        $ccmHQ = Store::firstOrCreate(['slug' => 'ccmaint-Anselme'], [
            'brand_id'              => $ccm->id,
            'name'                  => 'Crèmerie Chez Mamie Saint-Anselme',
            'franchise_number'      => 'CCM-001',
            'city'                  => 'Québec',
            'province'              => 'QC',
            'address'               => '680 Route Bégin, Saint-Anselme, QC G0R 2N0, Canada',
            'postal_code'           => 'G0R 2N0',
            'phone'                 => '(418) 982-2222',
            'email'                 => 'saint-anselme@cremeriechezmamie.com',
            'is_active'             => true,
            'project_type'          => 'Corpo',
            'start_date'            => '2019-05-01',
            'expected_opening_date' => '2019-06-15',
        ]);

        Store::firstOrCreate(['slug' => 'CCM-BEAUPORT'], [
            'brand_id'              => $ccm->id,
            'core_store_id'         => $ccmHQ->id,
            'name'                  => 'Crèmerie Chez Mamie CCM-BEAUPORT',
            'franchise_number'      => 'CCM-002',
            'city'                  => 'Québec',
            'province'              => 'QC',
            'address'               => '216 Rue Seigneuriale, Québec, QC G1E 4Y7, Canada',
            'postal_code'           => 'G1E 4Y7',
            'phone'                 => '+1 581-491-5797',
            'email'                 => 'beauport@cremeriechezmamie.com',
            'is_active'             => true,
            'project_type'          => 'Nouveau',
            'start_date'            => '2022-04-01',
            'expected_opening_date' => '2022-07-01',
        ]);

        $this->command->info('Stores seeded (5)');
    }
}