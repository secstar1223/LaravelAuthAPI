<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceTableSeeder extends Seeder
{

    static $prices = [
        [
            'product_id' => 1,
            'duration_id' => 1,
            'equipment_id' => 1,
            'total' => 60,
            'deposit' => 30,
        ],
        [
            'product_id' => 1,
            'duration_id' => 1,
            'equipment_id' => 1,
            'total' => 100,
            'deposit' => 40,
        ],
        [
            'product_id' => 1,
            'duration_id' => 1,
            'equipment_id' => 1,
            'total' => 120,
            'deposit' => 50,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$prices as $price) {
            DB::table('prices')->insert([
                'product_id'    => $price['product_id'],
                'duration_id'   => $price['duration_id'],
                'equipment_id'  => $price['equipment_id'],
                'total'         => $price['total'],
                'deposit'       => $price['deposit'],
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
