@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full flex flex-col items-center">
        <div class="mb-4">
            <!-- Success Icon -->
            <svg class="h-16 w-16 text-green-500 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="2"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-green-700 mb-2">Booking Approved!</h1>
        <p class="text-gray-700 mb-4 text-center">
            The meeting room booking has been successfully approved.<br>
            An email notification has been sent to the PIC.
        </p>
        <div class="w-full mb-6">
            <div class="bg-gray-50 rounded p-4 text-sm text-gray-700">
                <div><span class="font-semibold">Meeting Title:</span> {{ $booking->meeting_title }}</div>
                <div><span class="font-semibold">Room:</span> {{ $booking->meetingRoom->name ?? '-' }}</div>
                <div><span class="font-semibold">Date:</span> {{ $booking->date }}</div>
                <div><span class="font-semibold">Time:</span> {{ $booking->start_time }} - {{ $booking->end_time }}</div>
                <div><span class="font-semibold">PIC:</span> {{ $booking->pic_name }} ({{ $booking->pic_email }})</div>
            </div>
        </div>
        <div class="flex gap-3 w-full">
            <a href="/admin/bookings/pending" class="flex-1 bg-primary text-white py-2 rounded font-semibold text-center hover:bg-primary/90 transition">Back to Pending Approvals</a>
            <a href="/admin/bookings" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded font-semibold text-center hover:bg-gray-300 transition">View All Bookings</a>
        </div>
    </div>
</div>
@endsection 