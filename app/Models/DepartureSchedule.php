<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartureSchedule extends Model
{
    protected $appends = ['hash_id'];

    public function getHashIdAttribute()
    {
        return base64_encode($this->id);
    }

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class);
    }
}
