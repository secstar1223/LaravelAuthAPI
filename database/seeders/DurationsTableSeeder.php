<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DurationsTableSeeder extends Seeder
{

    static $durations = [
        [
            'name' => '4 hour',
            'product_id' => 1,
            'duration' => 14400,
            'buffer' => 0,
        ],
        [
            'name' => '8 hour',
            'product_id' => 1,
            'duration' => 28800,
            'buffer' => 0,
        ],
        [
            'name' => '2 day',
            'product_id' => 1,
            'duration' => 172800,
            'buffer' => 0,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$durations as $duration) {
            DB::table('durations')->insert([
                'name' => $duration['name'],
                'product_id' => $duration['product_id'],
                'duration' => $duration['duration'],
                'buffer' => $duration['buffer'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
