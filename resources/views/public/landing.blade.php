@extends('layouts.public')

@section('title', 'Meeting Room Booking - Room Availability & Today\'s Bookings')

@section('content')
<!-- Hero Banner -->
<div class="relative w-full bg-slate-900 py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Modern Background Pattern -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-[#FE8000]/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-[#FE8000]/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative w-full max-w-7xl mx-auto">
        <!-- Header Badge -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-md rounded-full px-6 py-3 border border-white/20">
                <div class="w-2 h-2 bg-[#FE8000] rounded-full animate-pulse"></div>
                <span class="text-sm font-medium text-white/90">Live Booking System</span>
                <div class="w-2 h-2 bg-[#FE8000] rounded-full animate-pulse"></div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="grid lg:grid-cols-2 gap-16 items-center mb-16">
            <!-- Left Content -->
            <div class="text-center lg:text-left">
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white mb-6 leading-tight">
                    <span class="text-[#FE8000]">
                        Smart
                    </span>
                    <br>
                    <span class="text-white">Room Booking</span>
                </h1>
                
                <p class="text-xl text-white/80 max-w-2xl leading-relaxed mb-8">
                    Experience seamless meeting room reservations with our intelligent booking platform. 
                    <span class="font-semibold text-[#FE8000]">Book smarter, meet better.</span>
                </p>
                
                <!-- Feature Pills -->
                <div class="flex flex-wrap gap-3 justify-center lg:justify-start mb-8">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 border border-white/20">
                        <i class='bx bx-zap text-[#FE8000] text-sm'></i>
                        <span class="text-sm font-medium text-white/90">Lightning Fast</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 border border-white/20">
                        <i class='bx bx-shield text-[#FE8000] text-sm'></i>
                        <span class="text-sm font-medium text-white/90">Secure</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 border border-white/20">
                        <i class='bx bx-mobile text-[#FE8000] text-sm'></i>
                        <span class="text-sm font-medium text-white/90">Mobile Ready</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Content - Modern Card -->
            <div class="relative">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl">
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative">
                            <div class="w-24 h-24 bg-[#FE8000] rounded-2xl flex items-center justify-center shadow-lg">
                                <i class='bx bx-calendar-star text-white text-4xl'></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-[#FE8000] rounded-full flex items-center justify-center">
                                <i class='bx bx-check text-white text-sm'></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-white mb-2">Ready to Book?</h3>
                        <p class="text-white/70 mb-6">Choose your preferred action below</p>
                        
                        <!-- Action Buttons -->
                        <div class="space-y-4">
                            <a href="{{ route('bookings.create') }}" class="block w-full bg-[#FE8000] text-white text-lg font-semibold py-4 px-6 rounded-xl hover:bg-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class='bx bx-plus-circle mr-2'></i>
                                Create New Booking
                            </a>
                            
                            <a href="{{ route('availability.index') }}" class="block w-full bg-white/10 backdrop-blur-sm text-white text-lg font-semibold py-4 px-6 rounded-xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                                <i class='bx bx-search mr-2'></i>
                                Check Availability
                            </a>
                            
                            <a href="{{ route('booking.lookup.form') }}" class="block w-full bg-white/5 backdrop-blur-sm text-white/80 text-lg font-semibold py-4 px-6 rounded-xl border border-white/10 hover:bg-white/10 hover:text-white transition-all duration-300">
                                <i class='bx bx-bookmark mr-2'></i>
                                View My Bookings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 w-full max-w-5xl mx-auto">
            <div class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="text-center">
                    <div class="text-3xl font-black text-[#FE8000] mb-2 group-hover:scale-110 transition-transform duration-300">
                        {{ $meetingRooms->count() }}
                    </div>
                    <div class="text-sm font-semibold text-white/90 uppercase tracking-wider">Available Rooms</div>
                    <div class="text-xs text-white/60 mt-1">Premium Spaces</div>
                </div>
            </div>
            <div class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="text-center">
                    <div class="text-3xl font-black text-[#FE8000] mb-2 group-hover:scale-110 transition-transform duration-300">
                        {{ $todayBookings->count() }}
                    </div>
                    <div class="text-sm font-semibold text-white/90 uppercase tracking-wider">Today's Bookings</div>
                    <div class="text-xs text-white/60 mt-1">Active Sessions</div>
                </div>
            </div>
            <div class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="text-center">
                    <div class="text-3xl font-black text-[#FE8000] mb-2 group-hover:scale-110 transition-transform duration-300">
                        {{ $meetingRooms->where('status', 'active')->count() }}
                    </div>
                    <div class="text-sm font-semibold text-white/90 uppercase tracking-wider">Ready Now</div>
                    <div class="text-xs text-white/60 mt-1">Instant Access</div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-12">
            <div class="inline-flex items-center gap-2 text-white/60 text-sm">
                <i class='bx bx-code-alt'></i>
                <span>Powered by Modern Technology</span>
                <i class='bx bx-code-alt'></i>
            </div>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <!-- Room Availability Section -->
    <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-12 lg:py-16 bg-white">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                Room Availability
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Browse all meeting rooms and check their current status and upcoming schedules
            </p>
        </div>
        
        <!-- Room Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 sm:gap-8">
            @foreach($meetingRooms as $room)
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                <!-- Room Header -->
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ $room->name }}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $roomAvailability[$room->id]['is_available'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $roomAvailability[$room->id]['is_available'] ? 'Available' : 'Occupied' }}
                        </span>
                    </div>
                    
                    <!-- Room Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-600">
                            <i class='bx bx-map-pin mr-3 text-primary'></i>
                            <span class="text-sm">{{ $room->location }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class='bx bx-group mr-3 text-primary'></i>
                            <span class="text-sm">Capacity: {{ $room->capacity }} people</span>
                        </div>
                        @if($room->description)
                        <div class="text-sm text-gray-600 line-clamp-2">
                            {{ Str::limit($room->description, 80) }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Status Info -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <p class="text-sm font-medium text-gray-900 mb-1">Status Today:</p>
                        <p class="text-sm text-gray-600">{{ $roomAvailability[$room->id]['next_available'] }}</p>
                    </div>
                    
                    <!-- Booking Button -->
                    <div class="flex flex-col sm:flex-row gap-2">
                        @if($roomAvailability[$room->id]['is_available'])
                            <a href="/bookings/create?room={{ $room->id }}" class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class='bx bx-calendar-plus mr-2'></i>
                                Book Now
                            </a>
                        @else
                            <a href="/bookings/create?room={{ $room->id }}" class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class='bx bx-calendar-check mr-2'></i>
                                View Schedule
                            </a>
                        @endif
                        <a href="/availability?room={{ $room->id }}" class="inline-flex items-center justify-center px-4 py-3 border border-primary text-primary hover:bg-primary hover:text-white text-sm font-semibold rounded-xl transition-all duration-300">
                            <i class='bx bx-time mr-2'></i>
                            Check
                        </a>
                    </div>
                </div>
                
                <!-- Today's Schedule -->
                @if($roomAvailability[$room->id]['bookings']->count() > 0)
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 sm:px-8 py-4 border-t border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-3">Today's Schedule:</p>
                    <div class="space-y-2">
                        @foreach($roomAvailability[$room->id]['bookings'] as $booking)
                        <div class="flex items-center justify-between text-sm bg-white rounded-lg p-3 shadow-sm">
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

    <!-- Today's Bookings Section -->
    @if($todayBookings->count() > 0)
    <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-12 lg:py-16 bg-gray-50">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                Today's Bookings
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                View all scheduled meetings for today across all meeting rooms
            </p>
        </div>
        
        <!-- Bookings Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-primary to-primary-dark">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Room</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Meeting Title</th>
                            <th class="hidden sm:table-cell px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Organizer</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($todayBookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class='bx bx-building mr-2 text-primary'></i>
                                    {{ $booking->meetingRoom->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate">{{ $booking->meeting_title }}</div>
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $booking->pic_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 