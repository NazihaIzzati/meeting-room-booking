@extends('layouts.master')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <h2 class="text-2xl font-bold mb-6 text-blue-700">Edit Room</h2>
    <form method="POST" action="/admin/rooms/{{ $room->id }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ $room->name }}" required class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Capacity</label>
            <input type="number" name="capacity" value="{{ $room->capacity }}" class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Location</label>
            <input type="text" name="location" value="{{ $room->location }}" class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="mt-1 block w-full rounded border px-3 py-2 text-sm">{{ $room->description }}</textarea>
        </div>
        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded font-semibold hover:bg-yellow-600">Update Room</button>
        <div class="mt-2"><a href="/admin/rooms" class="text-blue-600 underline">Back to Room List</a></div>
    </form>
</div>
@endsection 