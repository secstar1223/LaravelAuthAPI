<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AssetsTableSeeder extends Seeder
{
    static $assets = [
        [
            'name' => 'AnyTime',
            'team_id' => 1,
            'resource_tracking' => 1,
            'amount' => 4,
        ],
        [
            'name' => 'galileo',
            'team_id' => 1,
            'resource_tracking' => 1,
            'amount' => 4,
        ],
        [
            'name' => 'User2',
            'team_id' => 1,
            'resource_tracking' => 1,
            'amount' => 2,
        ],
        [
            'name' => 'User3',
            'team_id' => 1,
            'resource_tracking' => 0,
            'amount' => 2,
        ],
        [
            'name' => 'User4',
            'team_id' => 5,
            'resource_tracking' => 0,
            'amount' => 1,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$assets as $asset) {
            DB::table('assets')->insert([
                'name' => $asset['name'],
                'team_id' => $asset['team_id'],
                'resource_tracking' => $asset['resource_tracking'],
                'amount' => $asset['amount'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
