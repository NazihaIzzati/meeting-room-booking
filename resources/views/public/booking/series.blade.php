@extends('layouts.public')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-blue-700">Recurring Series</h2>
    <div class="mb-4">
        <form method="POST" action="/bookings/series/{{ $series->first()->parent_booking_id ?: $series->first()->id }}/cancel" onsubmit="return confirm('Cancel all future occurrences in this series?')">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded font-semibold hover:bg-red-700">Cancel All Future Occurrences</button>
        </form>
    </div>
    <table class="min-w-full text-sm">
        <thead>
            <tr class="bg-blue-50 text-blue-700">
                <th class="py-2 px-3">Date</th>
                <th class="py-2 px-3">Time</th>
                <th class="py-2 px-3">Room</th>
                <th class="py-2 px-3">Status</th>
                <th class="py-2 px-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($series as $booking)
            <tr>
                <td class="py-2 px-3">{{ $booking->date }}</td>
                <td class="py-2 px-3">{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                <td class="py-2 px-3">{{ $booking->meetingRoom->name ?? '-' }}</td>
                <td class="py-2 px-3">
                    <span class="px-2 py-1 rounded text-xs
                        @if($booking->status == 'approved') bg-green-100 text-green-700
                        @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-700
                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td class="py-2 px-3">
                    <a href="/bookings/{{ $booking->id }}/edit" class="text-blue-600 hover:underline text-xs">Edit</a>
                    @if($booking->status !== 'cancelled')
                        <form method="POST" action="/bookings/{{ $booking->id }}" class="inline-block" onsubmit="return confirm('Cancel this occurrence?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-xs ml-2">Cancel</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 