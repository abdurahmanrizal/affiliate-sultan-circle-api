<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kol;

class KolSeeder extends Seeder
{
    public function run(): void
    {
        Kol::create([
            'name' => 'Budi Santoso',
            'referral_code' => 'BUDI123',
        ]);

        Kol::create([
            'name' => 'Sinta Amelia',
            'referral_code' => 'SINTA123',
        ]);
    }
}