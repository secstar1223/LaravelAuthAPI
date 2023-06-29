<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentalQuestionsTableSeeder extends Seeder
{

    static $rentalQuestions = [
        [
            'product_id' => 1,
            'question_id' => 1,
            'is_require' => 0,
            'is_internal' => 0,
            'is_display' => 0,
            'is_checked' => 1,
        ],
        [
            'product_id' => 1,
            'question_id' => 2,
            'is_require' => 1,
            'is_internal' => 0,
            'is_display' => 1,
            'is_checked' => 1,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$rentalQuestions as $rentalQuestion) {
            DB::table('rental_questions')->insert([
                'product_id'    => $rentalQuestion['product_id'],
                'question_id'   => $rentalQuestion['question_id'],
                'is_require'    => $rentalQuestion['is_require'],
                'is_display'    => $rentalQuestion['is_display'],
                'is_internal'    => $rentalQuestion['is_internal'],
                'is_checked'    => $rentalQuestion['is_checked'],
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
