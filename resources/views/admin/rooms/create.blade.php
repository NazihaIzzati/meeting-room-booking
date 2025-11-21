@extends('layouts.admin')
@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <h2 class="text-2xl font-bold mb-6 text-blue-700">Add Room</h2>
    <form method="POST" action="/admin/rooms" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Capacity</label>
            <input type="number" name="capacity" class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Location</label>
            <input type="text" name="location" class="mt-1 block w-full rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="mt-1 block w-full rounded border px-3 py-2 text-sm"></textarea>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700">Add Room</button>
        <div class="mt-2"><a href="/admin/rooms" class="text-blue-600 underline">Back to Room List</a></div>
    </form>
</div>
@endsection 