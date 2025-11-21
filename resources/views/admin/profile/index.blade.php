@extends('layouts.public')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <h2 class="text-2xl font-bold mb-6 text-blue-700">My Profile</h2>
    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 px-4 py-2 rounded">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 text-red-700 bg-red-100 px-4 py-2 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="/profile" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Staff ID</label>
            <input type="text" name="staff_id" value="{{ old('staff_id', $user->staff_id) }}" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="password" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm" autocomplete="new-password">
            <small class="text-gray-500">Leave blank to keep current password.</small>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm" autocomplete="new-password">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold text-lg shadow hover:bg-blue-700 transition">Update Profile</button>
    </form>
</div>
@endsection 