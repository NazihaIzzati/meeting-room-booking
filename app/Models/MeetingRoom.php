<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    protected $fillable = ['name', 'location', 'capacity', 'description', 'status', 'remarks'];

    // Status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_UNAVAILABLE = 'unavailable';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_CLEANING = 'cleaning';

    // Get all available statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_UNAVAILABLE => 'Unavailable',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_CLEANING => 'Cleaning'
        ];
    }

    // Check if room is available for booking
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    // Get status badge class
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case self::STATUS_AVAILABLE:
                return 'bg-green-100 text-green-800';
            case self::STATUS_UNAVAILABLE:
                return 'bg-red-100 text-red-800';
            case self::STATUS_MAINTENANCE:
                return 'bg-yellow-100 text-yellow-800';
            case self::STATUS_CLEANING:
                return 'bg-blue-100 text-blue-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
