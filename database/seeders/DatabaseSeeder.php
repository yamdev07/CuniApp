<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FemellesSeeder::class,
            MalesSeeder::class,
            SailliesSeeder::class,  // Must be AFTER femelles and males
            MisesBasSeeder::class,  // Must be AFTER saillies
        ]);
    }
}