@extends('layouts.master')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-blue-100">
    <div class="bg-white shadow-lg rounded-lg p-10 w-full max-w-md flex flex-col items-center">
        <div class="mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m0-5V3m-8 9v6a2 2 0 002 2h4a2 2 0 002-2v-6" />
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-800 mb-2">Sign in to your account</h2>
        <p class="text-gray-500 mb-6">Welcome back! Please enter your credentials.</p>
        <form method="POST" action="/login" class="w-full space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required autofocus class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" value="{{ old('email') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>
            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded text-sm">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <button type="submit" class="w-full bg-primary text-white py-2 rounded font-semibold text-lg shadow hover:bg-primary/90 focus:ring-2 focus:ring-primary transition-all">Login</button>
        </form>
        <div class="text-sm mt-6 text-gray-600">Don't have an account? <a href="/register" class="text-blue-600 underline font-medium">Register</a></div>
    </div>
</div>
@endsection 