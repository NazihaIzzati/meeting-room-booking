<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomRequest;
use App\Http\Requests\Admin\UpdateRoomRequest;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of rooms (READ operation)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $rooms = MeetingRoom::orderBy('name')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room (READ operation - form)
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Store a newly created room (CREATE operation)
     *
     * @param StoreRoomRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoomRequest $request)
    {
        MeetingRoom::create($request->only('name', 'capacity', 'location', 'description'));
        return redirect('/admin/rooms')->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified room (READ operation)
     *
     * @param MeetingRoom $room
     * @return \Illuminate\View\View
     */
    public function show(MeetingRoom $room)
    {
        $bookings = $room->bookings()->with('user')->orderBy('date', 'desc')->paginate(15);
        return view('admin.rooms.show', compact('room', 'bookings'));
    }

    /**
     * Show the form for editing the specified room (READ operation - form)
     *
     * @param MeetingRoom $room
     * @return \Illuminate\View\View
     */
    public function edit(MeetingRoom $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified room (UPDATE operation)
     *
     * @param UpdateRoomRequest $request
     * @param MeetingRoom $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRoomRequest $request, MeetingRoom $room)
    {
        $room->update($request->only('name', 'capacity', 'location', 'description'));
        return redirect('/admin/rooms')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified room (DELETE operation)
     *
     * @param MeetingRoom $room
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MeetingRoom $room)
    {
        // Check if room has any bookings
        if ($room->bookings()->exists()) {
            return redirect('/admin/rooms')->with('error', 'Cannot delete room with existing bookings.');
        }

        $room->delete();
        return redirect('/admin/rooms')->with('success', 'Room deleted successfully.');
    }
}
