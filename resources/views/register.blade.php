@extends('layouts.master')
@section('content')
<div class="flex justify-center items-center min-h-screen">
    <form method="POST" action="/register" class="bg-white p-8 rounded shadow w-full max-w-md space-y-4">
        @csrf
        <h2 class="text-2xl font-bold mb-4">Register</h2>
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded border px-3 py-2 text-sm" value="{{ old('name') }}">
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" required class="mt-1 block w-full rounded border px-3 py-2 text-sm" value="{{ old('email') }}">
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input type="password" name="password" required class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded">
                <ul class="text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit" class="w-full bg-[#1b1b18] text-white py-2 rounded font-semibold hover:bg-black">Register</button>
        <div class="text-sm mt-2">Already have an account? <a href="/login" class="text-blue-600 underline">Login</a></div>
    </form>
</div>
@endsection 