@extends('layouts.master')
@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <h2 class="text-2xl font-bold mb-6 text-blue-700">Room Management</h2>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="mb-4 flex justify-end">
        <a href="/admin/rooms/create" class="bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700">Add Room</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs md:text-sm bg-white rounded shadow overflow-hidden">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Capacity</th>
                    <th class="px-4 py-2 text-left">Location</th>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                    <tr>
                        <td class="border-t px-4 py-2">{{ $room->name }}</td>
                        <td class="border-t px-4 py-2">{{ $room->capacity }}</td>
                        <td class="border-t px-4 py-2">{{ $room->location }}</td>
                        <td class="border-t px-4 py-2">{{ $room->description }}</td>
                        <td class="border-t px-4 py-2 space-x-2">
                            <a href="/admin/rooms/{{ $room->id }}/edit" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                            <form method="POST" action="/admin/rooms/{{ $room->id }}" class="inline" onsubmit="return confirm('Delete this room?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 