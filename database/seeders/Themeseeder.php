<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'name'            => 'Rouge',
                'primary_color'   => '#E40F18',
                'secondary_color' => '#E40F18',
                'font_family'     => 'Inter',
                'filament_color'  => 'danger',
            ],
            [
                'name'            => 'Rose',
                'primary_color'   => '#F4919A',
                'secondary_color' => '#F4919A',
                'font_family'     => 'Poppins',
                'filament_color'  => 'rose',
            ],
        ];

        foreach ($themes as $data) {
            Theme::firstOrCreate(['name' => $data['name']], $data);
        }

        $this->command->info('Themes seeded (' . count($themes) . ')');
    }
}