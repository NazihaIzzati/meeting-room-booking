@extends('layouts.admin')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <h2 class="text-2xl font-bold mb-4 text-blue-700">Approve Booking</h2>
    <div class="mb-6">
        <div class="mb-2"><span class="font-semibold text-gray-700">Meeting Title:</span> {{ $booking->meeting_title }}</div>
        <div class="mb-2"><span class="font-semibold text-gray-700">Room:</span> {{ $booking->meetingRoom->name ?? '-' }}</div>
        <div class="mb-2"><span class="font-semibold text-gray-700">Date:</span> {{ $booking->date }}</div>
        <div class="mb-2"><span class="font-semibold text-gray-700">Time:</span> {{ $booking->start_time }} - {{ $booking->end_time }}</div>
        <div class="mb-2"><span class="font-semibold text-gray-700">PIC:</span> {{ $booking->pic_name }} ({{ $booking->pic_email }}, {{ $booking->pic_phone }}, {{ $booking->pic_staff_id }})</div>
        <div class="mb-2"><span class="font-semibold text-gray-700">User:</span> {{ $booking->user->name ?? '-' }} ({{ $booking->user->email ?? '-' }})</div>
        <div class="mb-2"><span class="font-semibold text-gray-700">Status:</span> <span class="px-2 py-1 rounded text-xs @if($booking->status=='pending') bg-yellow-100 text-yellow-700 @elseif($booking->status=='approved') bg-green-100 text-green-700 @elseif($booking->status=='cancelled') bg-red-100 text-red-700 @endif">{{ ucfirst($booking->status) }}</span></div>
        @if($booking->recurrence)
            <div class="mb-2"><span class="font-semibold text-gray-700">Recurrence:</span> {{ ucfirst($booking->recurrence) }} until {{ $booking->recurrence_end_date }}
                <a href="/bookings/series/{{ $booking->parent_booking_id ?: $booking->id }}" class="ml-2 text-xs text-blue-700 underline">View Series</a>
            </div>
        @elseif($booking->parent_booking_id)
            <div class="mb-2"><span class="font-semibold text-gray-700">Recurrence:</span> Part of a series
                <a href="/bookings/series/{{ $booking->parent_booking_id }}" class="ml-2 text-xs text-blue-700 underline">View Series</a>
            </div>
        @endif
    </div>
    @if($booking->status == 'pending')
    <form id="admin-note-form" class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Admin Note (optional)</label>
        <textarea name="admin_note" id="admin_note" class="w-full rounded border px-3 py-2 text-sm" rows="2" placeholder="Add a note for this action (optional)"></textarea>
    </form>
    <div class="flex gap-4">
        <form method="POST" action="/bookings/{{ $booking->id }}/approve" onsubmit="return addNoteToForm(this)">
            @csrf
            <input type="hidden" name="admin_note" id="approve_note">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded font-semibold hover:bg-green-700">Approve</button>
        </form>
        <form method="POST" action="/bookings/{{ $booking->id }}/reject" onsubmit="return addNoteToForm(this)">
            @csrf
            <input type="hidden" name="admin_note" id="reject_note">
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded font-semibold hover:bg-yellow-700">Reject</button>
        </form>
    </div>
    <script>
    function addNoteToForm(form) {
        var note = document.getElementById('admin_note').value;
        if(form.action.endsWith('/approve')) {
            document.getElementById('approve_note').value = note;
        } else {
            document.getElementById('reject_note').value = note;
        }
        return true;
    }
    </script>
    @else
    <div class="text-gray-500">This booking has already been {{ $booking->status }}.</div>
    @endif
    @php
        $logs = \App\Models\AuditLog::with('user')->where('booking_id', $booking->id)->orderBy('created_at')->get();
    @endphp
    @if($logs->count())
    <div class="mt-8">
        <div class="font-bold text-blue-700 mb-2">Booking History</div>
        <ul class="divide-y divide-gray-100">
            @foreach($logs as $log)
                <li class="py-2 flex items-center gap-2">
                    <span class="text-xs text-gray-500">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                    <span class="text-xs font-semibold text-blue-700">{{ ucfirst($log->action) }}</span>
                    <span class="text-xs">@if($log->user){{ $log->user->name }}@else (system) @endif</span>
                    <span class="text-xs text-gray-400">{{ $log->details }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="mt-6">
        <a href="/admin/bookings/pending" class="text-blue-600 underline">Back to Approve Bookings</a>
    </div>
</div>
@endsection 