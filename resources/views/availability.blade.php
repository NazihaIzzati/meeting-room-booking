@extends('layouts.master')

@section('content')
@php
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
    function isBooked($bookings, $roomId, $date, $start, $end) {
        foreach ($bookings as $b) {
            if ($b->meeting_room_id == $roomId && $b->date == $date && $b->start_time < $end && $b->end_time > $start && $b->status !== 'cancelled') {
                return true;
            }
        }
        return false;
    }
@endphp
<div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Room Availability</h2>
    <div class="flex items-center justify-between mb-4">
        <button class="text-2xl px-2 py-1 rounded hover:bg-gray-100">&lt;</button>
        <div class="font-semibold text-lg">{{ \Carbon\Carbon::parse($date)->startOfWeek()->format('F d') }} - {{ \Carbon\Carbon::parse($date)->endOfWeek()->format('d, Y') }}</div>
        <button class="text-2xl px-2 py-1 rounded hover:bg-gray-100">&gt;</button>
        <div class="flex gap-2 ml-4">
            <button class="flex items-center gap-1 px-3 py-1 border rounded bg-orange-500 text-white font-semibold"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> Grid View</button>
            <button class="flex items-center gap-1 px-3 py-1 border rounded text-gray-700 bg-white font-semibold"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg> List View</button>
        </div>
    </div>
    <div class="flex flex-col gap-8">
        @foreach($rooms as $room)
        <div class="bg-white rounded-xl shadow p-6 flex flex-col gap-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-xl font-bold text-orange-500">{{ $room->name }}</div>
                    <div class="text-gray-500 text-sm flex items-center gap-4">
                        <span><svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 12V7a4 4 0 014-4h8a4 4 0 014 4v5"/></svg> Floor {{ $room->floor ?? '-' }}</span>
                        <span><svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><text x="12" y="16" text-anchor="middle" font-size="10" fill="currentColor">{{ $room->capacity }}</text></svg> Capacity: {{ $room->capacity ?? '-' }}</span>
                    </div>
                </div>
                <a href="#" class="bg-orange-500 text-white px-4 py-2 rounded font-semibold hover:bg-orange-600 transition">Book This Room</a>
            </div>
            <div>
                <div class="font-semibold text-gray-700 mb-1">Today's Availability</div>
                <div class="flex gap-2">
                    @foreach($slotLabels as $i => $label)
                        @php
                            $slot = $timeSlots[$i];
                            $booking = null;
                            foreach ($bookings as $b) {
                                if ($b->meeting_room_id == $room->id && $b->date == $date && $b->start_time <= $slot && $b->end_time > $slot && $b->status !== 'cancelled') {
                                    $booking = $b;
                                    break;
                                }
                            }
                        @endphp
                        @if($booking)
                            <div class="bg-red-100 text-red-500 px-3 py-1 rounded font-semibold text-xs min-w-[60px] text-center border border-red-200">Booked</div>
                        @else
                            <div class="bg-green-100 text-green-600 px-3 py-1 rounded font-semibold text-xs min-w-[60px] text-center border border-green-200">&nbsp;</div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div>
                <div class="font-semibold text-gray-700 mb-1 mt-4">Weekly Availability</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs border-separate border-spacing-y-2">
                        <thead>
                            <tr>
                                <th class="text-left py-1 px-2">Time</th>
                                @foreach($weekDays as $d)
                                    <th class="text-center py-1 px-2">{{ $d }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weeklyTimes as $label => [$start, $end])
                                <tr>
                                    <td class="py-1 px-2 font-semibold text-gray-700">{{ $label }}</td>
                                    @foreach($weekDays as $offset => $d)
                                        @php
                                            $dayDate = \Carbon\Carbon::parse($date)->startOfWeek()->addDays($offset);
                                            $isBooked = isBooked($bookings, $room->id, $dayDate->toDateString(), $start, $end);
                                        @endphp
                                        <td class="py-1 px-2">
                                            @if($isBooked)
                                                <div class="bg-red-100 text-red-500 px-3 py-1 rounded font-semibold text-xs text-center border border-red-200">Booked</div>
                                            @else
                                                <div class="bg-green-100 text-green-600 px-3 py-1 rounded font-semibold text-xs text-center border border-green-200">&nbsp;</div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection 