<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentTypesTableSeeder extends Seeder
{

    static $equipments = [
        [
            'name' => 'Deluxe',
            'product_id' => 1,
            'description' => 'something',
            'widget_image' => null,
            'widget_display' => 0,
            'min_amount' => 1,
            'max_amount' => 4,
            'require_min' => 1,
            'tax_template' => 1,
        ],
        [
            'name' => 'Sport',
            'product_id' => 1,
            'description' => 'something Specification',
            'widget_image' => null,
            'widget_display' => 0,
            'min_amount' => 1,
            'max_amount' => 50,
            'require_min' => 5,
            'tax_template' => 1,
        ],
        [
            'name' => 'Tour',
            'product_id' => 1,
            'description' => 'something Tour',
            'widget_image' => null,
            'widget_display' => 0,
            'min_amount' => 1,
            'max_amount' => 4,
            'require_min' => 1,
            'tax_template' => 1,
        ],

    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$equipments as $equipment) {
            DB::table('rental_equipment_types')->insert([
                'name' => $equipment['name'],
                'asset_id' => 1,
                'product_id' => $equipment['product_id'],
                'description' => $equipment['description'],
                'widget_image' => $equipment['widget_image'],
                'widget_display' => $equipment['widget_display'],
                'min_amount' => $equipment['min_amount'],
                'max_amount' => $equipment['max_amount'],
                'require_min' => $equipment['require_min'],
                'tax_template' => $equipment['tax_template'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
