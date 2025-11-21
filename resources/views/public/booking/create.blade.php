@extends('layouts.public')

@section('title', isset($booking) ? 'Edit Booking' : 'Book a Room')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-light to-white py-10 px-4">
    <div class="max-w-5xl w-full bg-white rounded-2xl shadow-lg p-12 flex flex-col items-center mx-auto mt-10 relative">
        <div class="absolute left-8 top-8 flex gap-3">
            <a href="/" class="flex items-center gap-2 text-primary font-semibold hover:underline hover:text-primary-dark transition-colors duration-200">
                <i class='bx bx-arrow-back'></i>
                Back to Home
            </a>
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-gray-600 font-semibold hover:text-primary transition-colors duration-200">
                    <i class='bx bx-grid-alt'></i>
                    Admin Dashboard
                </a>
            @endif
        </div>
        
        <div class="flex flex-col items-center mb-6">
            <div class="h-16 w-16 rounded-full bg-primary flex items-center justify-center shadow mb-3">
                <i class='bx bx-calendar-plus text-white text-3xl'></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ isset($booking) ? 'Edit Your Booking' : 'Book a Meeting Room' }}</h1>
            <p class="text-gray-500 text-center text-sm max-w-xs">Fill in the details below to reserve your meeting room. All fields marked with * are required.</p>
        </div>

        <form method="POST" action="{{ isset($booking) ? '/bookings/' . $booking->id . '/edit' : '/book' }}" class="w-full" id="booking-form">
            @csrf

            <!-- Room Selection -->
            <div class="mb-4">
                <label for="meeting_room_id" class="block text-sm font-medium text-gray-700 mb-2">Meeting Room *</label>
                <select 
                    id="meeting_room_id"
                    name="meeting_room_id" 
                    required 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200"
                >
                    <option value="">Select a meeting room...</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}"
                            @if((isset($booking) && $booking->meeting_room_id == $room->id) || old('meeting_room_id') == $room->id || request('room') == $room->id) selected @endif>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date and Time -->
            <div class="grid gap-6 mb-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input 
                        id="date"
                        type="date" 
                        name="date" 
                        required 
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('date', request('date', $booking->date ?? '')) }}"
                    >
                </div>

                @php
                    $startTimes = collect(range(8, 17))->map(function ($hour) {
                        return sprintf('%02d:00', $hour);
                    });
                    $endTimes = collect(range(9, 18))->map(function ($hour) {
                        return sprintf('%02d:00', $hour);
                    });
                @endphp

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($startTimes as $time)
                        @php
                            $formatted = \Carbon\Carbon::createFromFormat('H:i', $time)->format('g A');
                            $isSelected = old('start_time', request('start_time', $booking->start_time ?? '')) === $time;
                        @endphp
                        <label class="cursor-pointer">
                            <input 
                                type="radio" 
                                name="start_time" 
                                value="{{ $time }}" 
                                class="peer sr-only"
                                {{ $isSelected ? 'checked' : '' }}
                                required
                            >
                            <div class="w-full px-4 py-3 border border-gray-300 rounded-xl text-center text-sm font-semibold text-gray-700 transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                {{ $formatted }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($endTimes as $time)
                        @php
                            $formatted = \Carbon\Carbon::createFromFormat('H:i', $time)->format('g A');
                            $isSelected = old('end_time', $booking->end_time ?? '') === $time;
                        @endphp
                        <label class="cursor-pointer">
                            <input 
                                type="radio" 
                                name="end_time" 
                                value="{{ $time }}" 
                                class="peer sr-only"
                                {{ $isSelected ? 'checked' : '' }}
                                required
                            >
                            <div class="w-full px-4 py-3 border border-gray-300 rounded-xl text-center text-sm font-semibold text-gray-700 transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white">
                                {{ $formatted }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Select an end time at least one hour after the start time.</p>
                </div>
            </div>

            <!-- Meeting Details -->
            <div class="mb-4">
                <label for="meeting_title" class="block text-sm font-medium text-gray-700 mb-2">Meeting Title *</label>
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

            <!-- Person In Charge Details -->
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="pic_name" class="block text-sm font-medium text-gray-700 mb-2">Person In Charge Name *</label>
                    <input 
                        id="pic_name"
                        type="text" 
                        name="pic_name" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('pic_name', $booking->pic_name ?? '') }}"
                        placeholder="Full name"
                    >
                </div>
                <div>
                    <label for="pic_staff_id" class="block text-sm font-medium text-gray-700 mb-2">Staff ID <span class="text-gray-400">(optional)</span></label>
                    <input 
                        id="pic_staff_id"
                        type="text" 
                        name="pic_staff_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('pic_staff_id', $booking->pic_staff_id ?? '') }}"
                        placeholder="Employee ID"
                    >
                </div>
            </div>

            <!-- Recurrence -->
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="recurrence" class="block text-sm font-medium text-gray-700 mb-2">Recurrence</label>
                    <select id="recurrence" name="recurrence" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200">
                        <option value="">No recurrence</option>
                        <option value="daily" {{ old('recurrence', $booking->recurrence ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('recurrence', $booking->recurrence ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('recurrence', $booking->recurrence ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                <div>
                    <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700 mb-2">Recurrence End Date</label>
                    <input 
                        id="recurrence_end_date"
                        type="date" 
                        name="recurrence_end_date" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-200" 
                        value="{{ old('recurrence_end_date', $booking->recurrence_end_date ?? '') }}"
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold text-lg shadow transition">
                {{ isset($booking) ? 'Update Booking' : 'Book Room' }}
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize date input with today's date if empty
    if (!$('#date').val()) {
        $('#date').val(new Date().toISOString().split('T')[0]);
    }

    // Basic form validation
    $('#booking-form').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous errors
        $('.error-message').remove();
        $('input, select').removeClass('border-red-500').addClass('border-gray-300');

        // Validate required fields
        $('[required]').each(function() {
            if (!$(this).val()) {
                showError($(this), 'This field is required');
                isValid = false;
            }
        });


        // Validate date
        const selectedDate = new Date($('#date').val());
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            showError('#date', 'Date cannot be in the past');
            isValid = false;
        }

        // Validate time
        const startTime = $('#start_time').val();
        const endTime = $('#end_time').val();
        if (startTime && endTime && startTime >= endTime) {
            showError('#end_time', 'End time must be after start time');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    function showError(selector, message) {
        const $field = $(selector);
        $field.removeClass('border-gray-300').addClass('border-red-500');
        $field.after('<div class="error-message text-red-600 text-xs mt-1">' + message + '</div>');
    }


    // Remove error on input
    $(document).on('input change', 'input, select', function() {
        $(this).removeClass('border-red-500').addClass('border-gray-300');
        $(this).siblings('.error-message').remove();
    });
});
</script>
@endpush
@endsection