<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'kol_id',
        'referral_code',
        'user_name',
        'status',
        'departure_schedule_id',
        'ip',
        'user_agent',
        'visitor_hash',
        'is_unique',
    ];

    public function kol()
    {
        return $this->belongsTo(Kol::class);
    }
}
