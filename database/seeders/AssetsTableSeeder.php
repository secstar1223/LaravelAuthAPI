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
            'name' => 'secret',
            'team_id' => 1,
            'resource_tracking' => 1,
            'amount' => 4,
        ],
        [
            'name' => 'Sport',
            'team_id' => 1,
            'resource_tracking' => 1,
            'amount' => 5,
        ],
        [
            'name' => 'galileo',
            'team_id' => 2,
            'resource_tracking' => 1,
            'amount' => 4,
        ]
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
