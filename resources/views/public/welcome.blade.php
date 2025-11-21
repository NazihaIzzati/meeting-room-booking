@extends('layouts.public')

@section('title', 'Welcome')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full text-center">
        <div class="mb-8">
            <div class="mx-auto h-24 w-24 bg-primary rounded-full flex items-center justify-center mb-6">
                <i class='bx bx-calendar-check text-white text-5xl'></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Meeting Room Booking</h1>
            <p class="text-xl text-gray-600 mb-8">Efficiently manage and book meeting rooms for your organization</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="text-primary mb-4">
                    <i class='bx bx-calendar-plus text-4xl'></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Easy Booking</h3>
                <p class="text-gray-600">Book meeting rooms with just a few clicks. View availability in real-time.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="text-primary mb-4">
                    <i class='bx bx-time text-4xl'></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Real-time Availability</h3>
                <p class="text-gray-600">See which rooms are available and when. No more double bookings.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="text-primary mb-4">
                    <i class='bx bx-notification text-4xl'></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Instant Notifications</h3>
                <p class="text-gray-600">Get notified about booking confirmations and status changes.</p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/login" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary hover:bg-primary-dark transition-colors duration-200">
                <i class='bx bx-log-in mr-2'></i>
                Sign In
            </a>
            <a href="/register" class="inline-flex items-center justify-center px-8 py-3 border border-primary text-base font-medium rounded-lg text-primary bg-white hover:bg-gray-50 transition-colors duration-200">
                <i class='bx bx-user-plus mr-2'></i>
                Create Account
            </a>
        </div>
    </div>
</div>
@endsection
