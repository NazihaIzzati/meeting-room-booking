@extends('layouts.master')

@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8">
    <h2 class="text-2xl font-bold mb-6 text-blue-700">Audit Log</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs md:text-sm bg-white rounded shadow overflow-hidden">
            <thead>
                <tr class="bg-blue-50 text-blue-700">
                    <th class="py-2 px-3">Timestamp</th>
                    <th class="py-2 px-3">User</th>
                    <th class="py-2 px-3">Action</th>
                    <th class="py-2 px-3">Booking</th>
                    <th class="py-2 px-3">Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td class="py-2 px-3">{{ $log->created_at }}</td>
                    <td class="py-2 px-3">
                        @if($log->user)
                            {{ $log->user->name }}<br>
                            <span class="text-xs text-gray-500">{{ $log->user->email }}</span>
                        @else
                            <span class="text-gray-400">(system)</span>
                        @endif
                    </td>
                    <td class="py-2 px-3 font-semibold">{{ ucfirst($log->action) }}</td>
                    <td class="py-2 px-3">
                        @if($log->booking)
                            <a href="/bookings/{{ $log->booking->id }}/edit" class="text-blue-600 hover:underline">#{{ $log->booking->id }}</a><br>
                            <span class="text-xs text-gray-500">{{ $log->booking->meeting_title ?? '' }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-2 px-3">{{ $log->details }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">{{ $logs->links() }}</div>
    </div>
</div>
@endsection 