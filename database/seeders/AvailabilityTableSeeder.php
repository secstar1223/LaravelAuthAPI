<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AvailabilityTableSeeder extends Seeder
{

    static $availabilities = [
        [
            'times' => 'specific',
            'product_id' => 1,
            'start_time' => 0,
            'end_time' => 3600,
            'starts_every' => 900,
            'mon' => 1,
            'tue' => 1,
            'wed' => 1,
            'thu' => 1,
            'fri' => 1,
            'sat' => 1,
            'sun' => 1,
            'starts_specific' => '[]',
        ],
        [
            'times' => 'repeats',
            'product_id' => 1,
            'start_time' => 0,
            'end_time' => 3600,
            'starts_every' => 900,
            'mon' => 1,
            'tue' => 1,
            'wed' => 1,
            'thu' => 1,
            'fri' => 1,
            'sat' => 1,
            'sun' => 1,
            'starts_specific' => '[]',
        ],
        [
            'times' => 'specific',
            'product_id' => 1,
            'start_time' => 36000,
            'end_time' => 61200,
            'starts_every' => 0,
            'mon' => 1,
            'tue' => 1,
            'wed' => 1,
            'thu' => 0,
            'fri' => 1,
            'sat' => 1,
            'sun' => 1,
            'starts_specific' => '[36000, 61200]',
        ],
        [
            'times' => 'specific',
            'product_id' => 2,
            'start_time' => 36000,
            'end_time' => 64800,
            'starts_every' => 1800,
            'mon' => 1,
            'tue' => 1,
            'wed' => 1,
            'thu' => 1,
            'fri' => 1,
            'sat' => 1,
            'sun' => 1,
            'starts_specific' => '[]',
        ],
        [
            'times' => 'repeats',
            'product_id' => 2,
            'start_time' => 36000,
            'end_time' => 65700,
            'starts_every' => 1800,
            'mon' => 1,
            'tue' => 1,
            'wed' => 1,
            'thu' => 1,
            'fri' => 1,
            'sat' => 1,
            'sun' => 1,
            'starts_specific' => '[]',
        ],
        [
            'times' => 'specific',
            'product_id' => 2,
            'start_time' => 36000,
            'end_time' => 54000,
            'starts_every' => 0,
            'mon' => 0,
            'tue' => 0,
            'wed' => 0,
            'thu' => 0,
            'fri' => 1,
            'sat' => 1,
            'sun' => 1,
            'starts_specific' => '[36000, 54000]',
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$availabilities as $availability) {
            DB::table('availabilities')->insert([
                'times' => $availability['times'],
                'product_id' => $availability['product_id'],
                'start_time' => $availability['start_time'],
                'end_time' => $availability['end_time'],
                'starts_every' => $availability['starts_every'],
                'mon' => $availability['mon'],
                'tue' => $availability['tue'],
                'wed' => $availability['wed'],
                'thu' => $availability['thu'],
                'fri' => $availability['fri'],
                'sat' => $availability['sat'],
                'sun' => $availability['sun'],
                'starts_specific' => $availability['starts_specific'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
