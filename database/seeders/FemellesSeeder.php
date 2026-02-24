<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Femelle;

class FemellesSeeder extends Seeder
{
    public function run(): void
    {
        // Check if femelles already exist
        if (Femelle::count() > 0) {
            $this->command->info('⚠️  Femelles already exist. Skipping seeding.');
            return;
        }

        $femellesData = [
            [
                'code' => 'F001',
                'nom' => 'Bella',
                'race' => 'Californienne',
                'origine' => 'Interne',
                'date_naissance' => '2022-03-15',
                'etat' => 'Active',
            ],
            [
                'code' => 'F002',
                'nom' => 'Luna',
                'race' => 'Néerlandaise',
                'origine' => 'Achat',
                'date_naissance' => '2022-01-20',
                'etat' => 'Active',
            ],
        ];

        foreach ($femellesData as $femelleData) {
            Femelle::create($femelleData);
        }

        $this->command->info('✅ Femelles seeded successfully!');
    }
}