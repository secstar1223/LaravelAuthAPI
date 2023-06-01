<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamsTableSeeder extends Seeder
{
    static $teams = [
        [
            'name' => 'AnyTime',
            'email' => 'secstar1223@gmail.com',
            'user_id' => 1,
            'phone' => 12345678,
        ],
        [
            'name' => 'galileo',
            'email' => 'galileo0106@gmail.com',
            'user_id' => 2,
            'phone' => 12345678,
        ],
        [
            'name' => 'User2',
            'email' => 'user2@mail.com',
            'user_id' => 3,
            'phone' => 12345678,
        ],
        [
            'name' => 'User3',
            'email' => 'user3@mail.com',
            'user_id' => 4,
            'phone' => 12345678,
        ],
        [
            'name' => 'User4',
            'email' => 'user4@mail.com',
            'user_id' => 5,
            'phone' => 12345678,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$teams as $team) {
            DB::table('teams')->insert([
                'name' => $team['name'],
                'email' => $team['email'],
                'user_id' => $team['user_id'],
                'date_join' => date("Y-m-d H:i:s"),
                'website' => 'http://bookings247.co',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
