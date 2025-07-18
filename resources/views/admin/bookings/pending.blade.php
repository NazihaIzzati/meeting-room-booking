@extends('layouts.master')
@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <h2 class="text-2xl font-bold mb-2 text-blue-700">Approve Bookings</h2>
    <p class="mb-6 text-gray-600">Review and approve or reject pending meeting room bookings below.</p>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    <form method="GET" class="flex flex-wrap gap-2 mb-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
            <input type="text" name="search" value="{{ $filter_search ?? '' }}" placeholder="Title, PIC, User..." class="border rounded px-2 py-1 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Date</label>
            <input type="date" name="date" value="{{ $filter_date ?? '' }}" class="border rounded px-2 py-1 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Room</label>
            <select name="room" class="border rounded px-2 py-1 text-sm">
                <option value="">All Rooms</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @if(($filter_room ?? '') == $room->id) selected @endif>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold">Filter</button>
            @if($filter_search || $filter_date || $filter_room)
                <a href="?" class="ml-2 text-xs text-gray-500 underline">Clear</a>
            @endif
        </div>
    </form>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs md:text-sm bg-white rounded shadow overflow-hidden">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">Booking ID</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">Title</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">PIC</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">User</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">Room</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">Date</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">Time</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">Status</th>
                    <th class="py-2 px-4 border-b text-left text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td class="py-2 px-4 border-b text-sm">{{ $booking->id }}</td>
                        <td class="py-2 px-4 border-b text-sm">{{ $booking->meeting_title }}</td>
                        <td class="py-2 px-4 border-b text-sm">{{ $booking->pic_name }}</td>
                        <td class="py-2 px-4 border-b text-sm">{{ $booking->user->name ?? '-' }}</td>
                        <td class="py-2 px-4 border-b text-sm">{{ $booking->meetingRoom->name ?? '-' }}</td>
                        <td class="py-2 px-4 border-b text-sm">{{ $booking->date }}</td>
                        <td class="py-2 px-4 border-b text-sm">{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                        <td class="py-2 px-4 border-b text-sm">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Pending</span>
                        </td>
                        <td class="py-2 px-4 border-b text-sm">
                            <form method="POST" action="/bookings/{{ $booking->id }}/approve" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">Approve</button>
                            </form>
                            <form method="POST" action="/bookings/{{ $booking->id }}/reject" class="inline ml-1">
                                @csrf
                                <button type="submit" class="px-2 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700">Reject</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-400 py-8">No pending bookings to approve.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $bookings->links() }}</div>
</div>
@endsection 