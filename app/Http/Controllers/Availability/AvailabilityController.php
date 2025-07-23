<?php

namespace App\Http\Controllers\Availability;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Display room availability page (READ operation)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Public: no login check
        $date = $request->query('date', now()->toDateString());
        $rooms = MeetingRoom::all();
        $bookings = Booking::with('meetingRoom', 'user')->where('date', $date)->get();

        return view('public.booking.availability', compact('rooms', 'bookings', 'date'));
    }

    /**
     * Get availability data for a specific date (READ operation)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByDate(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $date = $request->query('date');
        if (!$date) {
            return response()->json(['error' => 'Date required'], 400);
        }

        $bookings = Booking::with(['meetingRoom', 'user'])
            ->where('date', $date)
            ->orderBy('start_time')
            ->get();

        return response()->json($bookings);
    }

    /**
     * Get room availability for a specific room and date (READ operation)
     *
     * @param Request $request
     * @param int $roomId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomAvailability(Request $request, $roomId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $date = $request->query('date', now()->toDateString());
        
        $bookings = Booking::with('user')
            ->where('meeting_room_id', $roomId)
            ->where('date', $date)
            ->where('status', 'approved')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'room_id' => $roomId,
            'date' => $date,
            'bookings' => $bookings
        ]);
    }
}
