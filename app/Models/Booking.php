<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'meeting_room_id', 'date', 'start_time', 'end_time', 'pic_name', 'pic_email', 'pic_phone', 'pic_staff_id', 'meeting_title', 'status'];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }
}
