<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $themeRed   = Theme::where('name', 'Rouge')->first();
        $themeRose = Theme::where('name', 'Rose')->first();

        $brands = [
            [
                'name'               => 'Salvatoré',
                'tag'                => 'SAL-',
                'theme_id'           => $themeRed?->id,
                'email_from_address' => 'no-reply@groupeabbatiello.com',
                'email_from_name'    => 'Salvatoré',
                'sms_phone_number'   => '+14503905653',
                'logo'               => 'brands/logos/salvat-logo.png',
                'favicon'            => 'brands/favicons/salvat-favicon.png',
                'design_config'      => [
                    'primary_color'   => '#E40F18',
                    'secondary_color' => '#E40F18',
                    'font_family'     => 'Inter',
                ],
                'links' => [
                    'website'  => 'https://salvatore.groupeabbatiello.com/',
                    'facebook' => 'https://web.facebook.com/PizzaSalvatoreCanada?locale=fr_FR',
                ],
            ],
            [
                'name'               => 'Crèmerie Chez Mamie',
                'tag'                => 'CCM',
                'theme_id'           => $themeRose?->id,
                'email_from_address' => 'no-reply@groupeabbatiello.com',
                'email_from_name'    => 'Crèmerie Chez Mamie',
                'sms_phone_number'   => '+14503905653',
                'logo'               => 'brands/logos/ccm-logo.png',
                'favicon'            => 'brands/favicons/ccm-favicon.png',
                'design_config'      => [
                    'primary_color'   => '#F4919A',
                    'secondary_color' => '#F4919A',
                    'font_family'     => 'Poppins',
                ],
                'links' => [
                    'website'   => 'https://chezmamie.groupeabbatiello.com/',
                    'facebook' => 'https://web.facebook.com/PizzaSalvatoreCanada?locale=fr_FR',
                ],
            ],
        ];

        foreach ($brands as $data) {
            Brand::firstOrCreate(['tag' => $data['tag']], $data);
        }

        $this->command->info('Brands seeded (' . count($brands) . ')');
    }
}