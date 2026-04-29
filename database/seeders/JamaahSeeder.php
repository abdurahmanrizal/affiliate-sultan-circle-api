<?php

namespace Database\Seeders;

use App\Models\Jamaah;
use App\Models\Kol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamaahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kol = Kol::first();

        if (!$kol) {
            $this->command->warn('No KOL found. Please seed KOLs first.');
            return;
        }

        Jamaah::create([
            'nama' => 'Ahmad Fauzi',
            'bin_binti' => 'bin Abdullah',
            'alamat_domisili' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-05-15',
            'nik_ktp' => '3171234567890001',
            'status_perkawinan' => 'Menikah',
            'pekerjaan' => 'Wiraswasta',
            'referral_code' => $kol->referral_code,
            'kol_id' => $kol->id,
        ]);

        Jamaah::create([
            'nama' => 'Siti Aisyah',
            'bin_binti' => 'binti Muhammad',
            'alamat_domisili' => 'Jl. Sudirman No. 25, Bandung',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-08-20',
            'nik_ktp' => '3271234567890002',
            'status_perkawinan' => 'Belum Menikah',
            'pekerjaan' => 'Guru',
            'referral_code' => $kol->referral_code,
            'kol_id' => $kol->id,
        ]);

        Jamaah::create([
            'nama' => 'Budi Santoso',
            'bin_binti' => null,
            'alamat_domisili' => 'Jl. Diponegoro No. 100, Surabaya',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1978-12-10',
            'nik_ktp' => '3571234567890003',
            'status_perkawinan' => 'Menikah',
            'pekerjaan' => 'Pegawai Swasta',
            'referral_code' => $kol->referral_code,
            'kol_id' => $kol->id,
            'status' => 'paid',
        ]);

        Jamaah::create([
            'nama' => 'Rina Wijaya',
            'bin_binti' => 'binti Susanto',
            'alamat_domisili' => 'Jl. Gatot Subroto No. 50, Medan',
            'tempat_lahir' => 'Medan',
            'tanggal_lahir' => '1995-03-25',
            'nik_ktp' => '1271234567890004',
            'status_perkawinan' => 'Belum Menikah',
            'pekerjaan' => 'Mahasiswa',
            'referral_code' => $kol->referral_code,
            'kol_id' => $kol->id,
            'status' => 'rejected',
        ]);
    }
}
