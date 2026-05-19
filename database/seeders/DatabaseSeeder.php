<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InitialDataSeeder::class,
            KeGiaDungSeeder::class,
            ProfessionalShelfExpansionSeeder::class,
            ShelfMarketingSeeder::class,
            ProfessionalShelfSeeder::class,
            ShelfImageSeeder::class,
            EnterpriseDataSeeder::class,
        ]);
    }
}
