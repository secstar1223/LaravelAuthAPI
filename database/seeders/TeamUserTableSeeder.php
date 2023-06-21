<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamUserTableSeeder extends Seeder
{
    static $teamusers = [
        [
            'team_id' => 1,
            'user_id' => 2,
            'role' => 'Administrator',
        ],
        [
            'team_id' => 1,
            'user_id' => 3,
            'role' => 'Editor',
        ],
        [
            'team_id' => 2,
            'user_id' => 1,
            'role' => 'Administrator',
        ],
        [
            'team_id' => 2,
            'user_id' => 4,
            'role' => 'Administrator',
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
            DB::table('team_user')->insert([
                'team_id' => $teamuser['team_id'],
                'user_id' => $teamuser['user_id'],
                'role' => $teamuser['role'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
