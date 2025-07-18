<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    protected $fillable = ['name', 'location', 'capacity', 'description'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
