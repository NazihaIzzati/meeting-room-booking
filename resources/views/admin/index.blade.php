@extends('layouts.public')

@section('title', 'Dashboard - Meeting Room Booking System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-gray-600 mt-1">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/bookings/create" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class='bx bx-plus mr-2'></i>
                        New Booking
                    </a>
                    <a href="/my-bookings" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200">
                        <i class='bx bx-calendar mr-2'></i>
                        My Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="w-full px-2 sm:px-4 lg:px-8 py-8">
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- My Bookings Card -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">My Bookings</p>
                        <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Booking::where('user_id', Auth::id())->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-calendar-check text-blue-600 text-2xl'></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Total bookings created</span>
                </div>
            </div>

            <!-- Pending Bookings Card -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-3xl font-bold text-orange-600">{{ \App\Models\Booking::where('user_id', Auth::id())->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-time text-orange-600 text-2xl'></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Awaiting approval</span>
                </div>
            </div>

            <!-- Approved Bookings Card -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-3xl font-bold text-green-600">{{ \App\Models\Booking::where('user_id', Auth::id())->where('status', 'approved')->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Confirmed bookings</span>
                </div>
            </div>

            <!-- Available Rooms Card -->
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Available Rooms</p>
                        <p class="text-3xl font-bold text-purple-600">{{ \App\Models\MeetingRoom::where('status', 'available')->count() }}</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class='bx bx-building text-purple-600 text-2xl'></i>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Ready for booking</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="/bookings/create" class="flex items-center p-4 bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            <i class='bx bx-plus-circle text-2xl mr-3'></i>
                            <div>
                                <p class="font-semibold">Book a Room</p>
                                <p class="text-sm opacity-90">Create a new meeting booking</p>
                            </div>
                        </a>
                        
                        <a href="/availability" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200">
                            <i class='bx bx-time text-2xl text-gray-600 mr-3'></i>
                            <div>
                                <p class="font-semibold text-gray-900">Check Availability</p>
                                <p class="text-sm text-gray-600">View room schedules</p>
                            </div>
                        </a>
                        
                        <a href="/my-bookings" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200">
                            <i class='bx bx-calendar text-2xl text-gray-600 mr-3'></i>
                            <div>
                                <p class="font-semibold text-gray-900">My Bookings</p>
                                <p class="text-sm text-gray-600">Manage your bookings</p>
                            </div>
                        </a>
                        
                        <a href="/profile" class="flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all duration-200">
                            <i class='bx bx-user text-2xl text-gray-600 mr-3'></i>
                            <div>
                                <p class="font-semibold text-gray-900">Profile Settings</p>
                                <p class="text-sm text-gray-600">Update your information</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        <a href="/my-bookings" class="text-primary hover:text-primary-dark text-sm font-medium">View All</a>
                    </div>
                    
                    @php
                        $recentBookings = \App\Models\Booking::where('user_id', Auth::id())
                            ->with('meetingRoom')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentBookings->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBookings as $booking)
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="flex-shrink-0">
                                    @if($booking->status === 'approved')
                                        <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class='bx bx-check text-green-600 text-lg'></i>
                                        </div>
                                    @elseif($booking->status === 'pending')
                                        <div class="h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            <i class='bx bx-time text-orange-600 text-lg'></i>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class='bx bx-x text-red-600 text-lg'></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->meeting_title }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->meetingRoom->name }} • {{ \Carbon\Carbon::parse($booking->date)->format('M j, Y') }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($booking->status === 'approved') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class='bx bx-calendar-x text-4xl text-gray-400 mb-4'></i>
                            <p class="text-gray-500">No recent bookings found</p>
                            <a href="/bookings/create" class="inline-flex items-center mt-2 text-primary hover:text-primary-dark font-medium">
                                <i class='bx bx-plus mr-1'></i>
                                Create your first booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Room Availability Overview -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Room Availability Overview</h3>
                <a href="/availability" class="text-primary hover:text-primary-dark text-sm font-medium">View Full Schedule</a>
            </div>
            
            @php
                $rooms = \App\Models\MeetingRoom::all();
                $today = now()->toDateString();
                $todayBookings = \App\Models\Booking::where('date', $today)
                    ->where('status', 'approved')
                    ->get();
            @endphp
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rooms as $room)
                    @php
                        $isBooked = $todayBookings->where('meeting_room_id', $room->id)->count() > 0;
                    @endphp
                    <div class="p-4 border border-gray-200 rounded-xl hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-gray-900">{{ $room->name }}</h4>
                            <div class="flex flex-col items-end space-y-1">
                                <!-- Room Status Badge -->
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $room->getStatusBadgeClass() }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                                <!-- Booking Status Badge -->
                                @if($room->isAvailable())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($isBooked) bg-red-100 text-red-800 @else bg-green-100 text-green-800 @endif">
                                        @if($isBooked) Occupied @else Available @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $room->location }} • {{ $room->capacity }} people</p>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-2">{{ $room->description }}</p>
                        
                        <!-- Remarks Section -->
                        @if($room->remarks)
                            <div class="mb-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class='bx bx-info-circle text-yellow-600 mt-0.5 mr-2 flex-shrink-0'></i>
                                    <p class="text-xs text-yellow-800">{{ $room->remarks }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-3">
                            @if($room->isAvailable())
                                <a href="/bookings/create?room={{ $room->id }}" class="inline-flex items-center text-sm text-primary hover:text-primary-dark font-medium">
                                    <i class='bx bx-calendar-plus mr-1'></i>
                                    Book Now
                                </a>
                            @else
                                <span class="inline-flex items-center text-sm text-gray-500 font-medium">
                                    <i class='bx bx-time mr-1'></i>
                                    Not Available
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 