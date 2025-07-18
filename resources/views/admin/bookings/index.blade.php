@extends('layouts.master')
@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-blue-700">All Bookings</h2>
        <a href="/admin/bookings/export" class="bg-green-600 text-white px-4 py-2 rounded font-semibold hover:bg-green-700">Export CSV</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs md:text-sm bg-white rounded shadow overflow-hidden">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Room</th>
                    <th class="px-4 py-2 text-left">Meeting Title</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Start Time</th>
                    <th class="px-4 py-2 text-left">End Time</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td class="border-t px-4 py-2">{{ $booking->user->name ?? '-' }}</td>
                        <td class="border-t px-4 py-2">{{ $booking->meetingRoom->name ?? '-' }}</td>
                        <td class="border-t px-4 py-2">{{ $booking->meeting_title }}</td>
                        <td class="border-t px-4 py-2">{{ $booking->date }}</td>
                        <td class="border-t px-4 py-2">{{ $booking->start_time }}</td>
                        <td class="border-t px-4 py-2">{{ $booking->end_time }}</td>
                        <td class="border-t px-4 py-2">
                            <form method="POST" action="/admin/bookings/{{ $booking->id }}" onsubmit="return confirm('Cancel this booking?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Cancel</button>
                            </form>
                            <a href="/bookings/{{ $booking->id }}/edit" class="text-blue-600 hover:underline text-xs ml-2">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 