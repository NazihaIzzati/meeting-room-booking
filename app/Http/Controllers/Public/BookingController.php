<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Notifications\BookingStatusChanged;
use App\Notifications\NewBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings (READ operation)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $bookings = Booking::where('user_id', Auth::id())
            ->with('meetingRoom')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('public.booking.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking (READ operation - form)
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $rooms = MeetingRoom::all();
        return view('public.booking.create', compact('rooms'));
    }

    /**
     * Store a newly created booking (CREATE operation)
     *
     * @param StoreBookingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $isGuest = !Auth::check();
        $rules = [
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'meeting_title' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'pic_email' => 'nullable|email|max:255',
            'pic_phone' => 'nullable|string|max:32',
            'pic_staff_id' => 'nullable|string|max:32',
            'recurrence' => 'nullable|in:daily,weekly,monthly',
            'recurrence_end_date' => 'nullable|date|after_or_equal:date',
        ];
        $request->validate($rules);

        $recurrence = $request->recurrence;
        $recurrenceEnd = $request->recurrence_end_date;
        $dates = [$request->date];

        if ($recurrence && $recurrenceEnd) {
            $current = \Carbon\Carbon::parse($request->date)->startOfDay();
            $end = \Carbon\Carbon::parse($recurrenceEnd)->startOfDay();
            
            // Generate recurring dates
            while (true) {
                if ($recurrence === 'daily') {
                    $current = $current->copy()->addDay();
                } elseif ($recurrence === 'weekly') {
                    $current = $current->copy()->addWeek();
                } elseif ($recurrence === 'monthly') {
                    $current = $current->copy()->addMonth();
                }
                
                // Check if current date is after the end date
                if ($current->toDateString() > $end->toDateString()) {
                    break;
                }
                
                $dates[] = $current->toDateString();
                
                // Safety check to prevent infinite loops
                if (count($dates) > 365) {
                    break;
                }
            }
        }

        // Check for conflicts for all dates (exclude cancelled bookings)
        foreach ($dates as $date) {
            $conflict = Booking::where('meeting_room_id', $request->meeting_room_id)
                ->where('date', $date)
                ->where('status', '!=', 'cancelled')
                ->where(function($q) use ($request) {
                    $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q2) use ($request) {
                          $q2->where('start_time', '<=', $request->start_time)
                             ->where('end_time', '>=', $request->end_time);
                      });
                })->exists();
            if ($conflict) {
                return redirect()->back()->withInput()->with('error', 'This room is already booked for the selected time slot. Please choose a different time.');
            }
        }

        // Create bookings
        $parentId = null;
        $referenceId = null;
        foreach ($dates as $i => $date) {
            $booking = Booking::create([
                'user_id' => Auth::id() ?: null,
                'meeting_room_id' => $request->meeting_room_id,
                'date' => $date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'pic_name' => $request->pic_name,
                'pic_email' => $request->pic_email,
                'pic_phone' => $request->pic_phone,
                'pic_staff_id' => $request->pic_staff_id,
                'meeting_title' => $request->meeting_title,
                'status' => 'approved',
                'recurrence' => $recurrence,
                'recurrence_end_date' => $recurrenceEnd,
                'parent_booking_id' => $parentId,
            ]);

            if (is_null($referenceId)) {
                $referenceId = $booking->id;
            }

            if ($i === 0) {
                $parentId = $booking->id;
                $booking->parent_booking_id = null;
                $booking->save();
            } else {
                $booking->parent_booking_id = $parentId;
                $booking->save();
            }

            AuditLog::create([
                'booking_id' => $booking->id, 
                'user_id' => Auth::id() ?: null, 
                'action' => 'created', 
                'details' => 'Booking created' . ($recurrence ? ' (recurring)' : '')
            ]);

            // Send notification to PIC email if provided
            if ($booking->pic_email) {
                Notification::route('mail', $booking->pic_email)->notify(new BookingStatusChanged($booking));
            }
            // Send notification to all admins
            $admins = \App\Models\User::where('is_admin', true)->get();
            Notification::send($admins, new NewBookingRequest($booking));
        }

        $referenceCode = $referenceId ? 'BR-' . str_pad((string) $referenceId, 6, '0', STR_PAD_LEFT) : null;
        $successMessage = 'Room booked successfully!';
        if ($referenceCode) {
            $successMessage .= ' Reference #: ' . $referenceCode . '.';
        }
        $successMessage .= ' Your booking is confirmed.';

        return redirect('/')->with('success', $successMessage);
    }

    /**
     * Display the specified booking (READ operation)
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function show(Booking $booking)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $canView = $user->isAdmin() || $booking->user_id == $user->id;

        if (!$canView) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['meetingRoom', 'user']);
        return view('booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking (READ operation - form)
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function edit(Booking $booking)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $today = now()->toDateString();
        $canEdit = $user->isAdmin() || ($booking->user_id == $user->id && $booking->date >= $today);

        if (!$canEdit) {
            abort(403, 'Unauthorized');
        }

        $rooms = MeetingRoom::all();
        return view('public.booking.edit', compact('booking', 'rooms'));
    }

    /**
     * Update the specified booking (UPDATE operation)
     *
     * @param UpdateBookingRequest $request
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $today = now()->toDateString();
        $canEdit = $user->isAdmin() || ($booking->user_id == $user->id && $booking->date >= $today);

        if (!$canEdit) {
            abort(403, 'Unauthorized');
        }

        // Double-booking check (exclude this booking)
        $conflict = Booking::where('meeting_room_id', $request->meeting_room_id)
            ->where('date', $request->date)
            ->where('id', '!=', $booking->id)
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['conflict' => 'This room is already booked for the selected time.'])->withInput();
        }

        $booking->update($request->only([
            'meeting_room_id', 'date', 'start_time', 'end_time', 'pic_name', 'pic_email', 'pic_phone', 'pic_staff_id', 'meeting_title'
        ]));

        AuditLog::create([
            'booking_id' => $booking->id, 
            'user_id' => Auth::id(), 
            'action' => 'updated', 
            'details' => 'Booking updated'
        ]);

        // If status was cancelled, set to pending on edit
        if ($booking->status === 'cancelled') {
            $booking->status = 'pending';
            $booking->save();
        }

        return redirect('/my-bookings')->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified booking (DELETE operation)
     *
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $booking)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $today = now()->toDateString();
        $canCancel = $user->isAdmin() || ($booking->user_id == $user->id && $booking->date >= $today);

        if (!$canCancel) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $booking->status = 'cancelled';
        $booking->save();

        AuditLog::create([
            'booking_id' => $booking->id, 
            'user_id' => Auth::id(), 
            'action' => 'cancelled', 
            'details' => 'Booking cancelled'
        ]);

        return response()->json(['success' => true]);
    }

    // Additional methods for series bookings

    /**
     * Display series bookings (READ operation)
     *
     * @param int $parent
     * @return \Illuminate\View\View
     */
    public function series($parent)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $series = Booking::where('id', $parent)->orWhere('parent_booking_id', $parent)->orderBy('date')->orderBy('start_time')->get();

        if ($series->isEmpty()) {
            abort(404);
        }

        $ownerId = $series->first()->user_id;
        if (!$user->isAdmin() && $user->id !== $ownerId) {
            abort(403, 'Unauthorized');
        }

        return view('public.booking.series', ['series' => $series]);
    }

    /**
     * Cancel series bookings (DELETE operation for series)
     *
     * @param int $parent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelSeries($parent)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $today = now()->toDateString();
        $series = Booking::where('id', $parent)->orWhere('parent_booking_id', $parent)->get();

        if ($series->isEmpty()) {
            abort(404);
        }

        $ownerId = $series->first()->user_id;
        if (!$user->isAdmin() && $user->id !== $ownerId) {
            abort(403, 'Unauthorized');
        }

        foreach ($series as $booking) {
            if ($booking->date >= $today && $booking->status !== 'cancelled') {
                $booking->status = 'cancelled';
                $booking->save();
                AuditLog::create([
                    'booking_id' => $booking->id, 
                    'user_id' => Auth::id(), 
                    'action' => 'cancelled', 
                    'details' => 'Series occurrence cancelled'
                ]);
            }
        }

        return back()->with('success', 'All future occurrences cancelled.');
    }

    /**
     * Get bookings by date (READ operation for API)
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
     * Show the booking lookup form (public)
     */
    public function lookupForm()
    {
        return view('public.booking.lookup');
    }

    /**
     * Process the booking lookup by reference number (public)
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'reference_no' => 'nullable|string|regex:/^BR-\d{6}$/',
            'date' => 'nullable|date',
        ], [
            'reference_no.regex' => 'Reference number must be in format BR-XXXXXX (e.g., BR-000001)',
        ]);
        
        $referenceNo = $request->input('reference_no');
        $date = $request->input('date');
        
        if (!$referenceNo && !$date) {
            return back()->withErrors(['reference_no' => 'Please enter a reference number or date to search.'])->withInput();
        }
        
        $query = \App\Models\Booking::query();
        
        if ($referenceNo) {
            // Extract booking ID from reference number (BR-000001 -> 1)
            $bookingId = (int) str_replace('BR-', '', $referenceNo);
            if ($bookingId > 0) {
                // Get the parent booking and all related bookings (for recurring bookings)
                $parentBooking = \App\Models\Booking::find($bookingId);
                if ($parentBooking) {
                    $parentId = $parentBooking->parent_booking_id ?? $parentBooking->id;
                    $query->where(function($q) use ($parentId, $bookingId) {
                        $q->where('id', $parentId)
                          ->orWhere('parent_booking_id', $parentId)
                          ->orWhere('id', $bookingId);
                    });
                } else {
                    // If booking not found, return empty result
                    $query->whereRaw('1 = 0'); // Force no results
                }
            } else {
                $query->whereRaw('1 = 0'); // Force no results
            }
        }
        
        if ($date) {
            $query->where('date', $date);
        }
        
        $bookings = $query->with('meetingRoom')->orderBy('date', 'desc')->orderBy('start_time')->get();
        
        return view('public.booking.lookup', [
            'bookings' => $bookings,
            'oldReferenceNo' => $referenceNo,
            'oldDate' => $date,
        ]);
    }
}
