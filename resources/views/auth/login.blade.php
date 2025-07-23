@extends('layouts.public')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center mb-6 shadow-lg">
                <i class='bx bx-lock-alt text-white text-4xl'></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Admin Login</h2>
            <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold mb-2">Admin Access Only</span>
            <p class="text-gray-600 mb-2">
                Please sign in with your admin credentials.<br>
                <span class="text-xs text-gray-400">User login is disabled.</span>
            </p>
        </div>
        <div class="bg-white py-8 px-8 shadow-xl rounded-xl border border-gray-100">
            <form method="POST" action="/login" class="space-y-6">
                @csrf
                <!-- Hidden fields for redirect parameters -->
                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif
                @if(request()->has('room'))
                    <input type="hidden" name="room" value="{{ request('room') }}">
                @endif
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm">
                </div>
                @if($errors->any())
                    <div class="text-red-600 text-sm">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium bg-primary text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 