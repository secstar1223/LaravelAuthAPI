<?php

namespace Database\Seeders;

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
            // UsersTableSeeder::class,
            // TeamsTableSeeder::class,
            // AssetsTableSeeder::class,
            // RentalProductsTableSeeder::class,
            // EquipmentTypesTableSeeder::class,
            // DurationsTableSeeder::class,
            // AvailabilityTableSeeder::class,
            // AvailabilityDurationsTableSeeder::class,
            // PriceTableSeeder::class,
            // TeamUserTableSeeder::class,
            // QuestionTableSeeder::class,
            // MultipleChoicesTableSeeder::class,
            RentalQuestionsTableSeeder::class,
        ]);
    }
}
