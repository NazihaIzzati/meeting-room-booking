@extends('layouts.public')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold">My Bookings</h2>
        <div class="flex flex-wrap gap-3 justify-end">
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to Admin Dashboard
                </a>
            @endif
            <a href="/dashboard" class="inline-block px-4 py-2 bg-[#1b1b18] text-white rounded hover:bg-black">Book a Room</a>
        </div>
    </div>
    @php
        $today = now()->toDateString();
    @endphp
    @if($bookings->isEmpty())
        <div class="p-4 bg-gray-100 text-gray-600 rounded">You have no bookings.</div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow rounded">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Room</th>
                        <th class="px-4 py-2 text-left">Meeting Title</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Start Time</th>
                        <th class="px-4 py-2 text-left">End Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="border-t px-4 py-2">{{ $booking->meetingRoom->name ?? '-' }}</td>
                            <td class="border-t px-4 py-2">{{ $booking->meeting_title }}</td>
                            <td class="border-t px-4 py-2">
                                @if($booking->recurrence)
                                    <span class="text-xs text-blue-500">Repeats: {{ ucfirst($booking->recurrence) }} until {{ $booking->recurrence_end_date }}</span>
                                    <a href="/bookings/series/{{ $booking->parent_booking_id ?: $booking->id }}" class="ml-2 text-xs text-blue-700 underline">View Series</a>
                                @elseif($booking->parent_booking_id)
                                    <a href="/bookings/series/{{ $booking->parent_booking_id }}" class="text-xs text-blue-700 underline">View Series</a>
                                @else
                                    <span class="text-xs text-gray-400">One-time</span>
                                @endif
                            </td>
                            <td class="border-t px-4 py-2">{{ $booking->start_time }}</td>
                            <td class="border-t px-4 py-2">{{ $booking->end_time }}</td>
                            <td class="py-2 px-3">
                                @if(auth()->user()->isAdmin() || $booking->date >= $today)
                                    <a href="/bookings/{{ $booking->id }}/edit" class="text-blue-600 hover:underline text-xs ml-2">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 