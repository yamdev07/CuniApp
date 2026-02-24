<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MiseBas;
use App\Models\Saillie;

class MisesBasSeeder extends Seeder
{
    public function run(): void
    {
        // Check if mises_bas already exist
        if (MiseBas::count() > 0) {
            $this->command->info('⚠️  Mises Bas already exist. Skipping seeding.');
            return;
        }

        // Get existing saillies
        $saillies = Saillie::all();

        if ($saillies->isEmpty()) {
            $this->command->info('⚠️  No saillies found. Skipping mises bas seeding.');
            return;
        }

        $misesBasData = [
            [
                'saillie_id' => $saillies->first()->id,
                'femelle_id' => $saillies->first()->femelle_id,
                'date_mise_bas' => '2025-08-01',
                'nb_vivant' => 6,
                'nb_mort_ne' => 1,
                'nb_retire' => 0,
                'nb_adopte' => 0,
                'date_sevrage' => '2025-09-12',
                'poids_moyen_sevrage' => 2.1,
            ],
        ];

        foreach ($misesBasData as $miseBasData) {
            MiseBas::create($miseBasData);
        }

        $this->command->info('✅ Mises Bas seeded successfully!');
    }
}