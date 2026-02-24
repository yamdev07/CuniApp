<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Saillie;
use App\Models\Femelle;
use App\Models\Male;
use Carbon\Carbon;

class SailliesSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing femelles and males
        $femelles = Femelle::all();
        $males = Male::all();
        
        // Skip if no femelles or males exist
        if ($femelles->isEmpty() || $males->isEmpty()) {
            $this->command->info('⚠️  No femelles or males found. Skipping saillies seeding.');
            return;
        }
        
        $sailliesData = [
            [
                'femelle_id' => $femelles->first()->id,
                'male_id' => $males->first()->id,
                'date_saillie' => '2025-07-01',
                'date_palpage' => '2025-07-15',
                'palpation_resultat' => '+',
                'date_mise_bas_theorique' => Carbon::parse('2025-07-01')->addDays(31),
            ],
            [
                'femelle_id' => $femelles->count() > 1 ? $femelles[1]->id : $femelles->first()->id,
                'male_id' => $males->count() > 1 ? $males[1]->id : $males->first()->id,
                'date_saillie' => '2025-07-05',
                'date_palpage' => '2025-07-20',
                'palpation_resultat' => '-',
                'date_mise_bas_theorique' => Carbon::parse('2025-07-05')->addDays(31),
            ],
        ];
        
        foreach ($sailliesData as $saillieData) {
            Saillie::create($saillieData);
        }
        
        $this->command->info('✅ Saillies seeded successfully!');
    }
}