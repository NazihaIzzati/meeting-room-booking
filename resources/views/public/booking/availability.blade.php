@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-light to-white py-10 px-4">
    <div class="max-w-5xl w-full bg-white rounded-2xl shadow-lg p-12 flex flex-col items-center mx-auto mb-10 relative">
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="absolute left-8 top-8 flex items-center gap-2 text-primary font-semibold hover:underline hover:text-primary-dark transition-colors duration-200">
                    <i class='bx bx-arrow-back'></i>
                    Back to Dashboard
                </a>
            @else
                <a href="/" class="absolute left-8 top-8 flex items-center gap-2 text-primary font-semibold hover:underline hover:text-primary-dark transition-colors duration-200">
                    <i class='bx bx-arrow-back'></i>
                    Back to Home
                </a>
            @endif
        @else
            <a href="/" class="absolute left-8 top-8 flex items-center gap-2 text-primary font-semibold hover:underline hover:text-primary-dark transition-colors duration-200">
                <i class='bx bx-arrow-back'></i>
                Back to Home
            </a>
        @endauth
        <div class="flex flex-col items-center mb-6">
            <div class="h-16 w-16 rounded-full bg-primary flex items-center justify-center shadow mb-3">
                <i class='bx bx-time text-white text-3xl'></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Check Meeting Room Availability</h1>
            <p class="text-gray-500 text-center text-sm max-w-xs">Select a date to see which rooms are available and book instantly.</p>
        </div>
        <form method="GET" class="w-full flex flex-col sm:flex-row items-center gap-4 mb-2">
            <div class="w-full sm:w-auto flex-1">
                <input type="date" name="date" value="{{ $date }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" />
            </div>
            <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold text-lg shadow transition">Check</button>
        </form>
    </div>
    <div class="flex flex-col items-center gap-8 mt-8">
        @foreach($rooms as $room)
        @php
            $roomBookings = $bookings->where('meeting_room_id', $room->id);
            $isAvailable = $roomBookings->isEmpty();
            $nextAvailable = $isAvailable ? 'Available all day' : 'See schedule below';
        @endphp
        <div class="max-w-5xl w-full bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-2xl transition-shadow duration-300 group flex flex-col mx-auto">
            <!-- Card Header -->
            <div class="flex items-center justify-between px-10 pt-10 pb-5 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <i class='bx bx-building text-primary text-3xl' title="Room Name"></i>
                    <span class="text-2xl font-bold text-gray-900">{{ $room->name }}</span>
                </div>
                <span class="ml-2 px-4 py-1 rounded-full text-sm font-semibold
                    @if($isAvailable) bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ $isAvailable ? 'Available' : 'Occupied' }}
                </span>
            </div>
            <!-- Card Details -->
            <div class="flex flex-col gap-6 px-10 py-8 text-lg flex-1">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <i class='bx bx-map-pin text-primary' title="Location"></i>
                        <span class="font-semibold text-gray-700">{{ $room->location }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class='bx bx-group text-primary' title="Capacity"></i>
                        <span class="font-semibold text-gray-700">Capacity: {{ $room->capacity }} people</span>
                    </div>
                    @if($room->description)
                    <div class="flex items-center gap-2">
                        <i class='bx bx-info-circle text-primary' title="Description"></i>
                        <span class="text-gray-700">{{ $room->description }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <i class='bx bx-calendar text-primary' title="Status"></i>
                        <span class="text-gray-700">Status Today: <span class="font-semibold">{{ $nextAvailable }}</span></span>
                    </div>
                </div>
            </div>
            <div class="px-10 pb-10 mt-4">
                @if($isAvailable)
                    <a href="{{ route('bookings.create', ['room' => $room->id]) }}" class="w-full inline-flex items-center justify-center px-8 py-4 bg-primary hover:bg-primary-dark text-white text-lg font-semibold rounded-xl transition-all duration-300 shadow-lg">
                        <i class='bx bx-calendar-plus mr-2'></i>
                        Book Now
                    </a>
                @else
                    <a href="{{ route('bookings.create', ['room' => $room->id]) }}" class="w-full inline-flex items-center justify-center px-8 py-4 bg-gray-500 hover:bg-gray-600 text-white text-lg font-semibold rounded-xl transition-all duration-300 shadow-lg">
                        <i class='bx bx-calendar-check mr-2'></i>
                        View Schedule
                    </a>
                @endif
            </div>
            <!-- Today's Schedule -->
            @if($roomBookings->count() > 0)
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-10 py-6 border-t border-gray-100">
                <p class="text-lg font-semibold text-gray-900 mb-3">Schedule for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}:</p>
                <div class="space-y-2">
                    @foreach($roomBookings->sortBy('start_time') as $booking)
                    <div class="flex items-center justify-between text-base bg-white rounded-lg p-4 shadow-sm">
                        <span class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span>
                        <span class="text-gray-600 truncate ml-2">{{ $booking->meeting_title }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection 