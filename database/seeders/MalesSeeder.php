<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Male;

class MalesSeeder extends Seeder
{
    public function run(): void
    {
        // Check if males already exist
        if (Male::count() > 0) {
            $this->command->info('⚠️  Males already exist. Skipping seeding.');
            return;
        }

        $malesData = [
            [
                'code' => 'M001',
                'nom' => 'Max',
                'race' => 'Néerlandaise',
                'origine' => 'Interne',
                'date_naissance' => '2022-05-12',
                'etat' => 'Active',
            ],
            [
                'code' => 'M002',
                'nom' => 'Rocky',
                'race' => 'Californienne',
                'origine' => 'Achat',
                'date_naissance' => '2021-12-20',
                'etat' => 'Active',
            ],
        ];

        foreach ($malesData as $maleData) {
            Male::create($maleData);
        }

        $this->command->info('✅ Males seeded successfully!');
    }
}