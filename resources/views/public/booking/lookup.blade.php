@extends('layouts.public')

@section('title', 'Find Your Bookings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-light to-white py-10 px-4">
    <div class="max-w-5xl w-full bg-white rounded-2xl shadow-lg p-12 flex flex-col items-center mx-auto mt-10 relative">
        <a href="/" class="absolute left-8 top-8 flex items-center gap-2 text-primary font-semibold hover:underline hover:text-primary-dark transition-colors duration-200">
            <i class='bx bx-arrow-back'></i>
            Back to Home
        </a>
        <div class="flex flex-col items-center mb-6">
            <div class="h-16 w-16 rounded-full bg-primary flex items-center justify-center shadow mb-3">
                <i class='bx bx-search-alt-2 text-white text-3xl'></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Check Your Booking Status</h1>
            <p class="text-gray-500 text-center text-sm max-w-xs">Enter your email address (used as Person In Charge) to view all your meeting room bookings and their status.</p>
        </div>
        <form method="POST" action="{{ route('booking.lookup') }}" class="w-full mb-6">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input id="email" name="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" value="{{ old('email', $oldEmail ?? '') }}">
                @error('email')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-gray-400">(optional)</span></label>
                <input id="date" name="date" type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" value="{{ old('date', $oldDate ?? '') }}">
                @error('date')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold text-lg shadow transition">Search Bookings</button>
        </form>
        @if(isset($bookings))
            @if($bookings->isEmpty())
                <div class="text-gray-500 text-center">No bookings found for this email.</div>
            @else
                <div class="w-full grid gap-8 mt-8 grid-cols-1">
                    @foreach($bookings as $booking)
                        <div class="w-full bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-lg p-0 border border-gray-100 hover:shadow-2xl transition-shadow duration-300 group">
                            <!-- Card Header -->
                            <div class="flex items-center justify-between px-10 pt-10 pb-5 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <i class='bx bx-book-content text-primary text-3xl' title="Meeting Title"></i>
                                    <span class="text-2xl font-bold text-gray-900">{{ $booking->meeting_title }}</span>
                                </div>
                                <span class="ml-2 px-4 py-1 rounded-full text-sm font-semibold
                                    @if($booking->status === 'approved') bg-green-100 text-green-800
                                    @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <!-- Card Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-10 py-8 text-lg">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-calendar text-primary' title="Date"></i>
                                        <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($booking->date)->format('l, d M Y') }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ \Carbon\Carbon::parse($booking->date)->format('l, d M Y') }}')" class="ml-2 text-base text-primary hover:underline" title="Copy date"><i class='bx bx-copy'></i></button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-time text-primary' title="Time"></i>
                                        <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span>
                                        <button onclick="navigator.clipboard.writeText('{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}')" class="ml-2 text-base text-primary hover:underline" title="Copy time"><i class='bx bx-copy'></i></button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-building text-primary' title="Room"></i>
                                        <span class="font-semibold text-gray-700">Room: {{ $booking->meetingRoom->name ?? '-' }}</span>
                                        <button onclick="navigator.clipboard.writeText('Room: {{ $booking->meetingRoom->name ?? '-' }}')" class="ml-2 text-base text-primary hover:underline" title="Copy room"><i class='bx bx-copy'></i></button>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-user text-primary' title="Organizer"></i>
                                        <span class="font-semibold text-gray-700">Organizer: {{ $booking->pic_name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-id-card text-primary' title="Staff ID"></i>
                                        <span class="font-semibold text-gray-700">Staff ID: {{ $booking->pic_staff_id }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class='bx bx-phone text-primary' title="Phone"></i>
                                        <span class="font-semibold text-gray-700">Phone: {{ $booking->pic_phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
@endsection 