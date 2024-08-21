<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'name' => 'notification_email',
            'setting' => 'notifications@shaz3e.com',
        ];

        DB::table('settings')->insert($settings);
    }
}
