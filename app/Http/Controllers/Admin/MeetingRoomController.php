<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;

class MeetingRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = MeetingRoom::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = MeetingRoom::getStatuses();
        return view('admin.rooms.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:meeting_rooms',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:available,unavailable,maintenance,cleaning',
            'remarks' => 'nullable|string'
        ]);

        MeetingRoom::create($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Meeting room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MeetingRoom $room)
    {
        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeetingRoom $room)
    {
        $statuses = MeetingRoom::getStatuses();
        return view('admin.rooms.edit', compact('room', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MeetingRoom $room)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:meeting_rooms,name,' . $room->id,
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:available,unavailable,maintenance,cleaning',
            'remarks' => 'nullable|string'
        ]);

        $room->update($request->all());

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Meeting room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeetingRoom $room)
    {
        // Check if room has any bookings
        if ($room->bookings()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Cannot delete room with existing bookings.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Meeting room deleted successfully.');
    }

    /**
     * Update room status only
     */
    public function updateStatus(Request $request, MeetingRoom $room)
    {
        $request->validate([
            'status' => 'required|in:available,unavailable,maintenance,cleaning',
            'remarks' => 'nullable|string'
        ]);

        $room->update([
            'status' => $request->status,
            'remarks' => $request->remarks
        ]);

        return redirect()->back()
            ->with('success', 'Room status updated successfully.');
    }
}
