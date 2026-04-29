<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    protected $fillable = [
        'nama',
        'bin_binti',
        'alamat_domisili',
        'tempat_lahir',
        'tanggal_lahir',
        'nik_ktp',
        'status_perkawinan',
        'pekerjaan',
        'referral_code',
        'having_passport',
        'keberangkatan',
        'status',
        'kol_id',
        'departure_schedule_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'having_passport' => 'boolean',
    ];

    public function kol()
    {
        return $this->belongsTo(Kol::class);
    }

    public function departureSchedule()
    {
        return $this->belongsTo(DepartureSchedule::class);
    }
}
