@extends('layouts.master')

@section('content')
<div x-data="bookingModal()">
<div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-8 text-[#FE8000]">Dashboard</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center mb-6">
                <span class="text-2xl font-bold text-[#FE8000] mr-2">Quick Stats</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="flex flex-col items-center bg-gradient-to-br from-orange-100 to-orange-50 rounded-xl p-5 shadow hover:shadow-lg transition">
                    <div class="bg-[#FE8000] bg-opacity-20 rounded-full p-3 mb-2">
                        <svg class="h-8 w-8 text-[#FE8000]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-[#FE8000]">{{ \App\Models\Booking::where('status', 'pending')->count() }}</div>
                    <div class="text-sm text-gray-700 mt-1 font-semibold">Pending Approvals</div>
                </div>
                <div class="flex flex-col items-center bg-gradient-to-br from-blue-100 to-blue-50 rounded-xl p-5 shadow hover:shadow-lg transition">
                    <div class="bg-blue-400 bg-opacity-20 rounded-full p-3 mb-2">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h16a1 1 0 001-1V7M5 7V5a2 2 0 012-2h10a2 2 0 012 2v2"/></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-blue-500">{{ \App\Models\MeetingRoom::count() }}</div>
                    <div class="text-sm text-gray-700 mt-1 font-semibold">Meeting Rooms</div>
                </div>
                <div class="flex flex-col items-center bg-gradient-to-br from-green-100 to-green-50 rounded-xl p-5 shadow hover:shadow-lg transition">
                    <div class="bg-green-400 bg-opacity-20 rounded-full p-3 mb-2">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4m0-5V3m-8 9v6a2 2 0 002 2h4a2 2 0 002-2v-6"/></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-green-500">{{ \App\Models\Booking::count() }}</div>
                    <div class="text-sm text-gray-700 mt-1 font-semibold">Total Bookings</div>
                </div>
                <div class="flex flex-col items-center bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl p-5 shadow hover:shadow-lg transition">
                    <div class="bg-purple-400 bg-opacity-20 rounded-full p-3 mb-2">
                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2M7 7a4 4 0 018 0v4a4 4 0 01-8 0V7z"/></svg>
                    </div>
                    <div class="text-3xl font-extrabold text-purple-500">{{ \App\Models\AuditLog::count() }}</div>
                    <div class="text-sm text-gray-700 mt-1 font-semibold">Audit Log</div>
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
            $bookingDate = $b->date instanceof \Carbon\Carbon ? $b->date->toDateString() : substr($b->date, 0, 10);
            $debug = [
                'roomId' => $roomId,
                'booking_room' => $b->meeting_room_id,
                'date' => $date,
                'booking_date' => $bookingDate,
                'start' => $start,
                'end' => $end,
                'booking_start' => $b->start_time,
                'booking_end' => $b->end_time,
                'status' => $b->status,
                'start_cmp' => \Carbon\Carbon::parse($b->start_time)->lt(\Carbon\Carbon::parse($end)),
                'end_cmp' => \Carbon\Carbon::parse($b->end_time)->gt(\Carbon\Carbon::parse($start)),
            ];
            echo '<!-- isBooked debug: ' . json_encode($debug) . ' -->';
            if (
                $b->meeting_room_id == $roomId &&
                $bookingDate == $date &&
                \Carbon\Carbon::parse($b->start_time)->lt(\Carbon\Carbon::parse($end)) &&
                \Carbon\Carbon::parse($b->end_time)->gt(\Carbon\Carbon::parse($start)) &&
                $b->status !== 'cancelled'
            ) {
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
                <button @click="openBookingModal({{ $room->id }}, '{{ addslashes($room->name) }}')" class="bg-[#FE8000] text-white px-4 py-2 rounded font-semibold hover:bg-orange-600 transition">Book This Room</button>
            </div>
            <div>
                <div class="font-semibold text-gray-700 mb-1">Today's Availability</div>
                <div class="flex gap-2">
                    @foreach($slotLabels as $i => $label)
                        @php
                            $slot = $timeSlots[$i];
                            $nextSlot = $timeSlots[$i+1] ?? null;
                            $isBooked = $nextSlot ? isBooked($bookings, $room->id, $date, $slot, $nextSlot) : false;
                        @endphp
                        <div class="flex flex-col items-center">
                            @if($isBooked)
                                <div class="bg-red-100 text-red-500 px-3 py-1 rounded font-semibold text-xs min-w-[60px] text-center border border-red-200">Booked</div>
                            @else
                                <div class="bg-green-100 text-green-600 px-3 py-1 rounded font-semibold text-xs min-w-[60px] text-center border border-green-200">&nbsp;</div>
                            @endif
                            <div class="text-[10px] text-gray-500 mt-1">
                                {{ $slot }}@if($nextSlot) - {{ $nextSlot }}@endif
                            </div>
                        </div>
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
                                            $dayDate = $carbonDate->copy()->startOfWeek()->addDays($offset);
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
<!-- Debug Section: Show all bookings for the selected week -->
<div class="bg-yellow-50 border border-yellow-300 rounded p-4 mt-8">
    <h3 class="font-bold text-yellow-700 mb-2">Debug: Approved Bookings for This Calendar View</h3>
    <ul class="text-xs text-yellow-900">
        @foreach($bookings as $b)
            <li>
                Room ID: {{ $b->meeting_room_id }}, Date: {{ $b->date }}, Start: {{ $b->start_time }}, End: {{ $b->end_time }}, Status: {{ $b->status }}, Title: {{ $b->meeting_title }}
            </li>
        @endforeach
        @if($bookings->isEmpty())
            <li>No approved bookings found for this week.</li>
        @endif
    </ul>
</div>

<!-- Booking Modal -->
<div x-show="show" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-8 relative">
        <button @click="close" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
        <h2 class="text-2xl font-bold mb-4 text-[#FE8000]">Book Room: <span x-text="roomName"></span></h2>
        <form method="POST" action="/book">
            @csrf
            <input type="hidden" name="meeting_room_id" :value="roomId">
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Date</label>
                <input type="date" name="date" class="w-full border rounded px-3 py-2" required :value="defaultDate">
            </div>
            <div class="mb-3 flex gap-2">
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Start Time</label>
                    <input type="time" name="start_time" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">End Time</label>
                    <input type="time" name="end_time" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Meeting Title</label>
                <input type="text" name="meeting_title" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">PIC Name</label>
                <input type="text" name="pic_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">PIC Email</label>
                <input type="email" name="pic_email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">PIC Phone</label>
                <input type="text" name="pic_phone" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">PIC Staff ID</label>
                <input type="text" name="pic_staff_id" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Recurrence</label>
                <select name="recurrence" class="w-full border rounded px-3 py-2">
                    <option value="">None</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Recurrence End Date</label>
                <input type="date" name="recurrence_end_date" class="w-full border rounded px-3 py-2">
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-[#FE8000] text-white px-6 py-2 rounded font-semibold hover:bg-orange-600 transition">Submit Booking</button>
            </div>
        </form>
    </div>
</div>
</div>
<script>
function bookingModal() {
    return {
        show: false,
        roomId: '',
        roomName: '',
        defaultDate: new Date().toISOString().split('T')[0],
        openBookingModal(id, name) {
            this.roomId = id;
            this.roomName = name;
            this.show = true;
        },
        close() {
            this.show = false;
        }
    }
}
</script>
@endsection 