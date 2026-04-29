<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartureScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            ['name' => '14 Juli 2026', 'status' => 'active'],
            ['name' => '25 Juli 2026', 'status' => 'active'],
        ];

        foreach ($schedules as $schedule) {
            DB::table('departure_schedules')->insert([
                'name' => $schedule['name'],
                'status' => $schedule['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
