<?php

use Illuminate\Support\Facades\Route;
use App\Models\MeetingRoom;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\BookingStatusChanged;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBookingRequest;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/');
    }
    $month = request('month', now()->month);
    $year = request('year', now()->year);
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
    return view('dashboard', compact('rooms', 'bookings', 'slotLabels', 'timeSlots', 'weeklyTimes', 'weekDays'));
});

Route::post('/book', function (Request $request) {
    $request->validate([
        'meeting_room_id' => 'required|exists:meeting_rooms,id',
        'date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'pic_name' => 'required|string',
        'pic_email' => 'required|email',
        'pic_phone' => 'required|string',
        'pic_staff_id' => 'required|string',
        'meeting_title' => 'required|string',
        'recurrence' => 'nullable|in:daily,weekly,monthly',
        'recurrence_end_date' => 'nullable|date|after_or_equal:date',
    ]);
    $recurrence = $request->recurrence;
    $recurrenceEnd = $request->recurrence_end_date;
    $dates = [$request->date];
    if ($recurrence && $recurrenceEnd) {
        $current = \Carbon\Carbon::parse($request->date);
        $end = \Carbon\Carbon::parse($recurrenceEnd);
        while (true) {
            if ($recurrence === 'daily') $current = $current->addDay();
            elseif ($recurrence === 'weekly') $current = $current->addWeek();
            elseif ($recurrence === 'monthly') $current = $current->addMonth();
            if ($current->gt($end)) break;
            $dates[] = $current->toDateString();
        }
    }
    // Check for conflicts for all dates
    foreach ($dates as $date) {
        $conflict = Booking::where('meeting_room_id', $request->meeting_room_id)
            ->where('date', $date)
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('start_time', '<=', $request->start_time)
                         ->where('end_time', '>=', $request->end_time);
                  });
            })->exists();
        if ($conflict) {
            return redirect('/dashboard')->with('error', 'This room is already booked for one or more of the selected dates/times.');
        }
    }
    // Create bookings
    $parentId = null;
    foreach ($dates as $i => $date) {
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'meeting_room_id' => $request->meeting_room_id,
            'date' => $date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'pic_name' => $request->pic_name,
            'pic_email' => $request->pic_email,
            'pic_phone' => $request->pic_phone,
            'pic_staff_id' => $request->pic_staff_id,
            'meeting_title' => $request->meeting_title,
            'status' => 'pending',
            'recurrence' => $recurrence,
            'recurrence_end_date' => $recurrenceEnd,
            'parent_booking_id' => $parentId,
        ]);
        if ($i === 0) {
            $parentId = $booking->id;
            $booking->parent_booking_id = null;
            $booking->save();
        } else {
            $booking->parent_booking_id = $parentId;
            $booking->save();
        }
        AuditLog::create(['booking_id' => $booking->id, 'user_id' => Auth::id(), 'action' => 'created', 'details' => 'Booking created' . ($recurrence ? ' (recurring)' : '')]);
        // Send notification to PIC email
        Notification::route('mail', $booking->pic_email)->notify(new \App\Notifications\BookingStatusChanged($booking));
        // Send notification to all admins
        $admins = \App\Models\User::where('is_admin', true)->get();
        Notification::send($admins, new \App\Notifications\NewBookingRequest($booking));
    }
    return redirect('/dashboard')->with('success', 'Room booked successfully!');
});

Route::get('/my-bookings', function () {
    if (!Auth::check()) {
        return redirect('/');
    }
    $bookings = Booking::where('user_id', Auth::id())->with('meetingRoom')->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();
    return view('my-bookings', compact('bookings'));
});

// Registration
Route::get('/register', function () {
    return view('register');
})->middleware('guest');
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    Auth::login($user);
    return redirect('/dashboard');
});

// Login
Route::get('/login', function () {
    return view('login');
})->middleware('guest');
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }
    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

// User profile management
Route::get('/profile', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }
    $user = Auth::user();
    return view('profile', compact('user'));
});
Route::post('/profile', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }
    $user = Auth::user();
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:32',
        'staff_id' => 'nullable|string|max:32',
        'password' => 'nullable|string|min:6|confirmed',
    ]);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->staff_id = $request->staff_id;
    if ($request->password) {
        $user->password = Hash::make($request->password);
    }
    $user->save();
    return redirect('/profile')->with('success', 'Profile updated successfully!');
});

// Admin: Room CRUD
Route::get('/admin/rooms', function () {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $rooms = MeetingRoom::all();
    return view('admin.rooms.index', compact('rooms'));
});
// Create room form
Route::get('/admin/rooms/create', function () {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    return view('admin.rooms.create');
});
// Store room
Route::post('/admin/rooms', function (Request $request) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $request->validate([
        'name' => 'required|string',
        'capacity' => 'nullable|integer',
        'location' => 'nullable|string',
        'description' => 'nullable|string',
    ]);
    MeetingRoom::create($request->only('name', 'capacity', 'location', 'description'));
    return redirect('/admin/rooms')->with('success', 'Room created.');
});
// Edit room form
Route::get('/admin/rooms/{room}/edit', function (MeetingRoom $room) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    return view('admin.rooms.edit', compact('room'));
});
// Update room
Route::put('/admin/rooms/{room}', function (Request $request, MeetingRoom $room) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $request->validate([
        'name' => 'required|string',
        'capacity' => 'nullable|integer',
        'location' => 'nullable|string',
        'description' => 'nullable|string',
    ]);
    $room->update($request->only('name', 'capacity', 'location', 'description'));
    return redirect('/admin/rooms')->with('success', 'Room updated.');
});
// Delete room
Route::delete('/admin/rooms/{room}', function (MeetingRoom $room) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $room->delete();
    return redirect('/admin/rooms')->with('success', 'Room deleted.');
});

// Admin: Booking management
Route::get('/admin/bookings', function () {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $bookings = Booking::with('meetingRoom', 'user')->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();
    return view('admin.bookings.index', compact('bookings'));
});
Route::get('/admin/bookings/pending', function (Request $request) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $query = Booking::with(['meetingRoom', 'user'])->where('status', 'pending');
    if ($search = $request->query('search')) {
        $query->where(function($q) use ($search) {
            $q->where('meeting_title', 'like', "%$search%")
              ->orWhere('pic_name', 'like', "%$search%")
              ->orWhereHas('user', function($uq) use ($search) {
                  $uq->where('name', 'like', "%$search%")
                     ->orWhere('email', 'like', "%$search%")
              ;});
        });
    }
    if ($date = $request->query('date')) {
        $query->where('date', $date);
    }
    if ($room = $request->query('room')) {
        $query->where('meeting_room_id', $room);
    }
    $bookings = $query->orderBy('date')->orderBy('start_time')->paginate(15)->withQueryString();
    $rooms = \App\Models\MeetingRoom::all();
    return view('admin.bookings.pending', [
        'bookings' => $bookings,
        'rooms' => $rooms,
        'filter_search' => $search,
        'filter_date' => $date,
        'filter_room' => $room,
    ]);
});
// Admin: Cancel booking
Route::delete('/admin/bookings/{booking}', function (Booking $booking) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $booking->delete();
    AuditLog::create(['booking_id' => $booking->id, 'user_id' => Auth::id(), 'action' => 'cancelled', 'details' => 'Booking cancelled']);
    return redirect('/admin/bookings')->with('success', 'Booking cancelled.');
});

// AJAX: Admin approve a booking
Route::post('/bookings/{booking}/approve', function (Booking $booking, Request $request) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    if ($booking->status !== 'pending') {
        return redirect()->back()->with('error', 'Only pending bookings can be approved.');
    }
    $booking->status = 'approved';
    $booking->save();
    $note = $request->input('admin_note');
    $details = 'Booking approved';
    if ($note) { $details .= ' | Note: ' . $note; }
    AuditLog::create(['booking_id' => $booking->id, 'user_id' => Auth::id(), 'action' => 'approved', 'details' => $details]);
    if ($booking->user && $booking->user->email) {
        $booking->user->notify(new BookingStatusChanged($booking));
    }
    $booking->load('meetingRoom');
    return view('admin.bookings.approve-success', compact('booking'));
});
// AJAX: Admin reject (cancel) a booking
Route::post('/bookings/{booking}/reject', function (Booking $booking, Request $request) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    if ($booking->status !== 'pending') {
        return response()->json(['error' => 'Only pending bookings can be rejected.'], 400);
    }
    $booking->status = 'cancelled';
    $booking->save();
    $note = $request->input('admin_note');
    $details = 'Booking cancelled';
    if ($note) { $details .= ' | Note: ' . $note; }
    AuditLog::create(['booking_id' => $booking->id, 'user_id' => Auth::id(), 'action' => 'cancelled', 'details' => $details]);
    if ($booking->user && $booking->user->email) {
        $booking->user->notify(new BookingStatusChanged($booking));
    }
    return response()->json(['success' => true]);
});

// Edit/reschedule booking
Route::get('/bookings/{booking}/edit', function (Booking $booking) {
    if (!Auth::check()) {
        return redirect('/login');
    }
    $user = Auth::user();
    $today = now()->toDateString();
    $canEdit = $user->isAdmin() || ($booking->user_id == $user->id && $booking->date >= $today);
    if (!$canEdit) {
        abort(403, 'Unauthorized');
    }
    $rooms = \App\Models\MeetingRoom::all();
    return view('edit-booking', compact('booking', 'rooms'));
});
Route::post('/bookings/{booking}/edit', function (Request $request, Booking $booking) {
    if (!Auth::check()) {
        return redirect('/login');
    }
    $user = Auth::user();
    $today = now()->toDateString();
    $canEdit = $user->isAdmin() || ($booking->user_id == $user->id && $booking->date >= $today);
    if (!$canEdit) {
        abort(403, 'Unauthorized');
    }
    $request->validate([
        'meeting_room_id' => 'required|exists:meeting_rooms,id',
        'date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'pic_name' => 'required|string',
        'pic_email' => 'required|email',
        'pic_phone' => 'required|string',
        'pic_staff_id' => 'required|string',
        'meeting_title' => 'required|string',
    ]);
    // Double-booking check (exclude this booking)
    $conflict = \App\Models\Booking::where('meeting_room_id', $request->meeting_room_id)
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
    AuditLog::create(['booking_id' => $booking->id, 'user_id' => Auth::id(), 'action' => 'updated', 'details' => 'Booking updated']);
    // If status was cancelled, set to pending on edit
    if ($booking->status === 'cancelled') {
        $booking->status = 'pending';
        $booking->save();
    }
    return redirect('/my-bookings')->with('success', 'Booking updated successfully!');
});

// View all occurrences in a recurring series
Route::get('/bookings/series/{parent}', function ($parent) {
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
    return view('series-bookings', ['series' => $series]);
});

// Cancel all future occurrences in a recurring series
Route::post('/bookings/series/{parent}/cancel', function ($parent) {
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
            AuditLog::create(['booking_id' => $booking->id, 'user_id' => Auth::id(), 'action' => 'cancelled', 'details' => 'Series occurrence cancelled']);
        }
    }
    return back()->with('success', 'All future occurrences cancelled.');
});

// API: Get bookings for a specific date (for dashboard details)
Route::get('/bookings/by-date', function (Request $request) {
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
});

// AJAX: Cancel a booking (user can cancel own future, admin can cancel any)
Route::delete('/bookings/{booking}', function (Booking $booking) {
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
    AuditLog::create(['booking_id' => $booking->id, 'user_id' => Auth::id(), 'action' => 'cancelled', 'details' => 'Booking cancelled']);
    return response()->json(['success' => true]);
});

// Room availability visualization
Route::get('/availability', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }
    $date = $request->query('date', now()->toDateString());
    $rooms = \App\Models\MeetingRoom::all();
    $bookings = \App\Models\Booking::with('meetingRoom', 'user')->where('date', $date)->get();
    return view('availability', compact('rooms', 'bookings', 'date'));
});

// Admin: View audit log
Route::get('/admin/audit-logs', function () {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $logs = \App\Models\AuditLog::with(['booking', 'user'])->orderByDesc('created_at')->paginate(30);
    return view('admin.audit-logs.index', compact('logs'));
});

// Admin: Export bookings as CSV
Route::get('/admin/bookings/export', function () {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $bookings = \App\Models\Booking::with(['meetingRoom', 'user'])->orderBy('date')->get();
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="bookings.csv"',
    ];
    $columns = [
        'ID', 'Date', 'Start Time', 'End Time', 'Room', 'Meeting Title', 'PIC Name', 'PIC Email', 'PIC Phone', 'PIC Staff ID', 'User', 'Status', 'Recurrence', 'Recurrence End', 'Created At', 'Updated At'
    ];
    $callback = function() use ($bookings, $columns) {
        $out = fopen('php://output', 'w');
        fputcsv($out, $columns);
        foreach ($bookings as $b) {
            fputcsv($out, [
                $b->id,
                $b->date,
                $b->start_time,
                $b->end_time,
                $b->meetingRoom->name ?? '',
                $b->meeting_title,
                $b->pic_name,
                $b->pic_email,
                $b->pic_phone,
                $b->pic_staff_id,
                $b->user->name ?? '',
                $b->status,
                $b->recurrence,
                $b->recurrence_end_date,
                $b->created_at,
                $b->updated_at,
            ]);
        }
        fclose($out);
    };
    return response()->stream($callback, 200, $headers);
});

// Admin: Show booking approval page
Route::get('/bookings/{booking}/approve', function (\App\Models\Booking $booking) {
    if (!Auth::check() || !Auth::user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }
    $booking->load(['meetingRoom', 'user']);
    return view('admin.bookings.approve', compact('booking'));
});
