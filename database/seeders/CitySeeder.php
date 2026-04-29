<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Jakarta',
            'Surabaya',
            'Bandung',
            'Medan',
            'Semarang',
            'Makassar',
            'Palembang',
            'Tangerang',
            'South Tangerang',
            'Bogor',
            'Bandar Lampung',
            'Padang',
            'Yogyakarta',
            'Malang',
            'Bekasi',
            'Surakarta',
            'Denpasar',
            'Pekanbaru',
            'Balikpapan',
            'Pontianak',
            'Banjarmasin',
            'Samarinda',
            'Tasikmalaya',
            'Cimahi',
            'Depok',
            'Batam',
            'Ambon',
            'Manado',
            'Kupang',
            'Mataram',
            'Jayapura',
            'Jambi',
            'Ternate',
            'Bengkulu',
            'Pangkalpinang',
            'Cirebon',
            'Cilacap',
            'Purwokerto',
            'Tegal',
            'Magelang',
            'Pekalongan',
            'Salatiga',
            'Kudus',
            'Blora',
            'Kediri',
            'Madiun',
            'Probolinggo',
            'Pasuruan',
            'Mojokerto',
            'Gresik',
            'Situbondo',
            'Bondowoso',
            'Banyuwangi',
            'Lumajang',
            'Jember',
        ];

        foreach ($cities as $city) {
            City::create(['name' => $city]);
        }
    }
}
