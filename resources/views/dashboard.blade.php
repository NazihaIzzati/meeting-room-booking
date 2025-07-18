@extends('layouts.master')

@section('content')
<div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-8 text-[#FE8000]">Dashboard</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col gap-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-xl font-bold text-[#FE8000]">Quick Stats</div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="bg-[#FE8000] bg-opacity-10 rounded-lg p-4 flex flex-col items-center">
                    <div class="text-3xl font-bold text-[#FE8000]">{{ \App\Models\Booking::where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-gray-700 mt-1">Pending Approvals</div>
                </div>
                <div class="bg-[#FE8000] bg-opacity-10 rounded-lg p-4 flex flex-col items-center">
                    <div class="text-3xl font-bold text-[#FE8000]">{{ \App\Models\MeetingRoom::count() }}</div>
                    <div class="text-sm text-gray-700 mt-1">Meeting Rooms</div>
                </div>
                <div class="bg-[#FE8000] bg-opacity-10 rounded-lg p-4 flex flex-col items-center">
                    <div class="text-3xl font-bold text-[#FE8000]">{{ \App\Models\Booking::count() }}</div>
                    <div class="text-sm text-gray-700 mt-1">Total Bookings</div>
                </div>
                <div class="bg-[#FE8000] bg-opacity-10 rounded-lg p-4 flex flex-col items-center">
                    <div class="text-3xl font-bold text-[#FE8000]">{{ \App\Models\AuditLog::count() }}</div>
                    <div class="text-sm text-gray-700 mt-1">Audit Log</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col gap-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-xl font-bold text-[#FE8000]">Recent Activity</div>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach(\App\Models\AuditLog::with(['user', 'booking'])->orderByDesc('created_at')->limit(5)->get() as $log)
                    <li class="py-2 flex items-center gap-2">
                        <span class="text-xs text-gray-500">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                        <span class="text-xs font-semibold text-[#FE8000]">{{ ucfirst($log->action) }}</span>
                        <span class="text-xs">@if($log->user){{ $log->user->name }}@else (system) @endif</span>
                        <span class="text-xs">@if($log->booking)Booking #{{ $log->booking->id }}@endif</span>
                        <span class="text-xs text-gray-400">{{ $log->details }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@php
    $carbonDate = \Carbon\Carbon::parse(request('date', now()));
    $date = $carbonDate->toDateString();
    $weekRange = $carbonDate->startOfWeek()->format('F d') . ' - ' . $carbonDate->endOfWeek()->format('d, Y');
    $prevWeek = $carbonDate->copy()->subWeek()->toDateString();
    $nextWeek = $carbonDate->copy()->addWeek()->toDateString();
    function isBooked($bookings, $roomId, $date, $start, $end) {
        foreach ($bookings as $b) {
            if ($b->meeting_room_id == $roomId && $b->date == $date && $b->start_time < $end && $b->end_time > $start && $b->status !== 'cancelled') {
                return true;
            }
        }
        return false;
    }
@endphp
<div class="mt-12">
    <div class="flex items-center justify-between mb-4 gap-2">
        <a href="?date={{ $prevWeek }}" class="text-2xl px-2 py-1 rounded hover:bg-gray-100">&lt;</a>
        <div class="font-semibold text-lg text-gray-700 flex-1 text-center">{{ $weekRange }}</div>
        <a href="?date={{ $nextWeek }}" class="text-2xl px-2 py-1 rounded hover:bg-gray-100">&gt;</a>
        <a href="?date={{ now()->toDateString() }}" class="ml-2 px-4 py-1 bg-[#FE8000] text-white rounded font-semibold hover:bg-orange-600 transition">Today</a>
    </div>
    <h2 class="text-2xl font-bold mb-6 text-[#FE8000]">Room Availability</h2>
    <div class="flex flex-col gap-8">
        @foreach($rooms as $room)
        <div class="bg-white rounded-xl shadow p-6 flex flex-col gap-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-xl font-bold text-[#FE8000]">{{ $room->name }}</div>
                    <div class="text-gray-500 text-sm flex items-center gap-4">
                        <span><svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 12V7a4 4 0 014-4h8a4 4 0 014 4v5"/></svg> Floor {{ $room->floor ?? '-' }}</span>
                        <span><svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><text x="12" y="16" text-anchor="middle" font-size="10" fill="currentColor">{{ $room->capacity }}</text></svg> Capacity: {{ $room->capacity ?? '-' }}</span>
                    </div>
                </div>
                <a href="#" class="bg-[#FE8000] text-white px-4 py-2 rounded font-semibold hover:bg-orange-600 transition">Book This Room</a>
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
                                            $dayDate = \Carbon\Carbon::now()->startOfWeek()->addDays($offset);
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