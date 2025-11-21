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
        $today = Carbon::today()->format('Y-m-d');
        
        // Get all meeting rooms
        $meetingRooms = MeetingRoom::all();

        // Get today's bookings for quick overview (single source of truth)
        $todayBookings = Booking::with(['meetingRoom', 'user'])
            ->where('date', $today)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        // Get upcoming bookings (next 7 days)
        $upcomingBookings = Booking::with(['meetingRoom', 'user'])
            ->where('date', '>=', Carbon::today())
            ->where('date', '<=', Carbon::today()->addDays(7))
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Get room availability for today using the same todayBookings data
        $roomAvailability = [];
        foreach ($meetingRooms as $room) {
            // Filter today's bookings for this specific room
            $todayRoomBookings = $todayBookings->where('meeting_room_id', $room->id);
            $roomAvailability[$room->id] = [
                'room' => $room,
                'bookings' => $todayRoomBookings,
                'is_available' => $todayRoomBookings->isEmpty(),
                'next_available' => $this->getNextAvailableTime($todayRoomBookings, Carbon::today())
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
     * @param \Illuminate\Support\Collection $todayBookings
     * @param Carbon $date
     * @return string|null
     */
    private function getNextAvailableTime($todayBookings, $date)
    {
        if ($todayBookings->isEmpty()) {
            return 'Available all day';
        }

        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        
        // Find the next available slot
        foreach ($todayBookings->sortBy('end_time') as $booking) {
            if ($booking->end_time > $currentTime) {
                return 'Available from ' . Carbon::parse($booking->end_time)->format('h:i A');
            }
        }

        return 'Fully booked today';
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
