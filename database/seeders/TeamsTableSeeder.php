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
            'name' => "secret's team",
            'email' => 'secstar1223@gmail.com',
            'user_id' => 1,
            'phone' => "+19282514577",
        ],
        [
            'name' => "galileo's team",
            'email' => 'galileo0106@gmail.com',
            'user_id' => 2,
            'phone' => "+19282514577",
        ],
        [
            'name' => "user1's team",
            'email' => 'user1@gmail.com',
            'user_id' => 2,
            'phone' => "+19282514577",
        ],
        [
            'name' => "user2's team",
            'email' => 'user2@gmail.com',
            'user_id' => 2,
            'phone' => "+19282514577",
        ]
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
