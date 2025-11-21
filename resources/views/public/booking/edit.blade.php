@extends('layouts.public')

@section('title', isset($booking) ? 'Edit Booking' : 'Book a Room')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ isset($booking) ? 'Edit Booking' : 'Book a Room' }}</h1>
            <a href="/my-bookings" class="text-primary hover:text-primary-dark transition-colors duration-200">
                <i class='bx bx-arrow-back mr-1'></i>Back to My Bookings
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start space-x-2">
                <i class='bx bx-error-circle text-xl mt-0.5'></i>
                <div>
                    <p class="font-medium">Please fix the following errors:</p>
                    <ul class="mt-1 text-sm list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ isset($booking) ? '/bookings/' . $booking->id . '/edit' : '/book' }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="meeting_room_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-building mr-2'></i>Meeting Room
                    </label>
                    <select 
                        id="meeting_room_id"
                        name="meeting_room_id" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200"
                    >
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ (isset($booking) && $booking->meeting_room_id == $room->id) || old('meeting_room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }} (Capacity: {{ $room->capacity ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="meeting_title" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-calendar-event mr-2'></i>Meeting Title
                    </label>
                    <input 
                        id="meeting_title"
                        type="text" 
                        name="meeting_title" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('meeting_title', $booking->meeting_title ?? '') }}"
                        placeholder="Enter meeting title"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-calendar mr-2'></i>Date
                    </label>
                    <input 
                        id="date"
                        type="date" 
                        name="date" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('date', $booking->date ?? '') }}"
                    >
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-time mr-2'></i>Start Time
                    </label>
                    <input 
                        id="start_time"
                        type="time" 
                        name="start_time" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('start_time', $booking->start_time ?? '') }}"
                    >
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-time-five mr-2'></i>End Time
                    </label>
                    <input 
                        id="end_time"
                        type="time" 
                        name="end_time" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('end_time', $booking->end_time ?? '') }}"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="pic_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-user mr-2'></i>PIC Name
                    </label>
                    <input 
                        id="pic_name"
                        type="text" 
                        name="pic_name" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('pic_name', $booking->pic_name ?? '') }}"
                    >
                </div>
                <div>
                    <label for="pic_email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-envelope mr-2'></i>PIC Email
                    </label>
                    <input 
                        id="pic_email"
                        type="email" 
                        name="pic_email" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('pic_email', $booking->pic_email ?? '') }}"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="pic_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-phone mr-2'></i>PIC Phone
                    </label>
                    <input 
                        id="pic_phone"
                        type="text" 
                        name="pic_phone" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('pic_phone', $booking->pic_phone ?? '') }}"
                    >
                </div>
                <div>
                    <label for="pic_staff_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-id-card mr-2'></i>PIC Staff ID
                    </label>
                    <input 
                        id="pic_staff_id"
                        type="text" 
                        name="pic_staff_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('pic_staff_id', $booking->pic_staff_id ?? '') }}"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="recurrence" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-repeat mr-2'></i>Recurrence
                    </label>
                    <select id="recurrence" name="recurrence" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">None</option>
                        <option value="daily" {{ old('recurrence', $booking->recurrence ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('recurrence', $booking->recurrence ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('recurrence', $booking->recurrence ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                <div>
                    <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class='bx bx-calendar-check mr-2'></i>Recurrence End Date
                    </label>
                    <input 
                        id="recurrence_end_date"
                        type="date" 
                        name="recurrence_end_date" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('recurrence_end_date', $booking->recurrence_end_date ?? '') }}"
                    >
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded font-semibold hover:bg-primary-dark transition">
                    {{ isset($booking) ? 'Update Booking' : 'Book Room' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 