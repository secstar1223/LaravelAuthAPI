<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentalProductsTableSeeder extends Seeder
{

    static $products = [
        [
            'name' => 'Bob Marina',
            'description' => 'rent at bobs',
            'tax_template' => 1,
            'team_id' => '1',
        ],
        [
            'name' => 'jan marina',
            'description' => 'something else fun',
            'tax_template' => 2,
            'team_id' => '1',
        ],
        [
            'name' => 'Leena marina',
            'description' => 'something else fun',
            'tax_template' => 1,
            'team_id' => '1',
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$products as $product) {
            DB::table('rental_products')->insert([
                'name' => $product['name'],
                'description' => $product['description'],
                'tax_template' => $product['tax_template'],
                'team_id' => $product['team_id'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
