<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AvailabilityDurationsTableSeeder extends Seeder
{

    static $availabilityDurations = [
        [
            'availability_id' => 1,
            'duration_id' => 1,
        ],
        [
            'availability_id' => 1,
            'duration_id' => 2,
        ],
        [
            'availability_id' => 1,
            'duration_id' => 3,
        ],
        [
            'availability_id' => 2,
            'duration_id' => 1,
        ],
        [
            'availability_id' => 2,
            'duration_id' => 2,
        ],
        [
            'availability_id' => 2,
            'duration_id' => 3,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$availabilityDurations as $availabilityDuration) {
            DB::table('availability_duration')->insert([
                'availability_id' => $availabilityDuration['availability_id'],
                'duration_id' => $availabilityDuration['duration_id'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
