<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kol extends Model
{
    protected $fillable = [
        'name',
        'email',
        'number_whatsapp',
        'tiktok_instagram_account',
        'city_id',
        'referral_code',
        'total_click',
        'total_register',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'referral_code', 'referral_code');
    }
}
