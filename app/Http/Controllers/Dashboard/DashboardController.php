<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard (READ operation)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $bookings = Booking::with('meetingRoom', 'user')
            ->whereBetween('date', [$startOfCalendar->toDateString(), $endOfCalendar->toDateString()])
            ->where('status', 'approved')
            ->get();

        $rooms = MeetingRoom::all();
        $startHour = 8;
        $endHour = 18;
        $timeSlots = [];
        for ($h = $startHour; $h < $endHour; $h++) {
            $timeSlots[] = sprintf('%02d:00', $h);
        }
        $slotLabels = ['8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'];
        $weeklyTimes = [
            '8-10 AM' => ['08:00', '10:00'],
            '10-12 PM' => ['10:00', '12:00'],
            '1-3 PM' => ['13:00', '15:00'],
            '3-5 PM' => ['15:00', '17:00'],
        ];
        $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];

        return view('admin.index', compact('rooms', 'bookings', 'slotLabels', 'timeSlots', 'weeklyTimes', 'weekDays'));
    }

    /**
     * Get dashboard data for AJAX requests (READ operation)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        
        $bookings = Booking::with('meetingRoom', 'user')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('status', 'approved')
            ->get();

        return response()->json([
            'bookings' => $bookings,
            'month' => $month,
            'year' => $year
        ]);
    }
}
