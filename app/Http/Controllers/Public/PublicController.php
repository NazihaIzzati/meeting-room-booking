<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicController extends Controller
{
    /**
     * Display the landing page with all rooms and booking details (READ operation)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all meeting rooms with their current bookings
        $meetingRooms = MeetingRoom::with(['bookings' => function($query) {
            $query->where('date', '>=', Carbon::today())
                  ->where('status', 'approved')
                  ->orderBy('date')
                  ->orderBy('start_time');
        }])->get();

        // Get today's bookings for quick overview
        $todayBookings = Booking::with(['meetingRoom', 'user'])
            ->where('date', Carbon::today())
            ->where('status', 'approved')
            ->orderBy('start_time')
            ->get();

        // Get upcoming bookings (next 7 days)
        $upcomingBookings = Booking::with(['meetingRoom', 'user'])
            ->where('date', '>=', Carbon::today())
            ->where('date', '<=', Carbon::today()->addDays(7))
            ->where('status', 'approved')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Get room availability for today
        $roomAvailability = [];
        foreach ($meetingRooms as $room) {
            $todayRoomBookings = $room->bookings->where('date', Carbon::today()->format('Y-m-d'));
            $roomAvailability[$room->id] = [
                'room' => $room,
                'bookings' => $todayRoomBookings,
                'is_available' => $todayRoomBookings->isEmpty(),
                'next_available' => $this->getNextAvailableTime($room, Carbon::today())
            ];
        }

        return view('public.landing', compact(
            'meetingRooms',
            'todayBookings',
            'upcomingBookings',
            'roomAvailability'
        ));
    }

    /**
     * Get next available time for a room
     *
     * @param MeetingRoom $room
     * @param Carbon $date
     * @return string|null
     */
    private function getNextAvailableTime($room, $date)
    {
        $todayBookings = $room->bookings->where('date', $date->format('Y-m-d'));
        
        if ($todayBookings->isEmpty()) {
            return 'Available all day';
        }

        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        
        // Find the next available slot
        foreach ($todayBookings as $booking) {
            if ($booking->end_time > $currentTime) {
                return 'Available from ' . Carbon::parse($booking->end_time)->format('h:i A');
            }
        }

        return 'Available tomorrow';
    }

    /**
     * Display the welcome page (alias for index)
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        return $this->index();
    }
}
