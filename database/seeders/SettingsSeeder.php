<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'emailDomain',
                'value' => 'dimacoftest.com',
            ],
            [
                'key' => 'adminUsername',
                'value' => 'admin', 
            ],
            [
                'key' => 'maxdaysCanje',
                'value' => 90,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
