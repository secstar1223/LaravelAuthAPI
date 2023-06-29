<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MultipleChoicesTableSeeder extends Seeder
{
    static $teamusers = [
        [
            'question_id' => 3,
            'choice' => 'Fort-Loudon `s answer 1',
        ],
        [
            'question_id' => 3,
            'choice' => 'Fort-Loudon `s answer 2',
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$teamusers as $teamuser) {
            DB::table('multiple_choices')->insert([
                'question_id' => $teamuser['question_id'],
                'choice' => $teamuser['choice'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
