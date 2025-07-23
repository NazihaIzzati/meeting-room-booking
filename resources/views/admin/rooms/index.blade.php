@extends('layouts.public')

@section('title', 'Manage Meeting Rooms - Admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Meeting Rooms</h1>
                    <p class="text-gray-600 mt-1">View and manage all meeting rooms</p>
                </div>
                <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors duration-200">
                    <i class='bx bx-plus mr-2'></i>
                    Add New Room
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Room Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $room->name }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $room->getStatusBadgeClass() }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600 mb-2">
                        <i class='bx bx-map-pin mr-2'></i>
                        {{ $room->location }}
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <i class='bx bx-group mr-2'></i>
                        {{ $room->capacity }} people
                    </div>
                </div>

                <!-- Room Description -->
                <div class="p-6 border-b border-gray-200">
                    <p class="text-sm text-gray-700">{{ $room->description }}</p>
                </div>

                <!-- Remarks Section -->
                @if($room->remarks)
                <div class="p-6 border-b border-gray-200 bg-yellow-50">
                    <div class="flex items-start">
                        <i class='bx bx-info-circle text-yellow-600 mt-0.5 mr-2 flex-shrink-0'></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-800 mb-1">Remarks</p>
                            <p class="text-sm text-yellow-700">{{ $room->remarks }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <i class='bx bx-edit mr-1'></i>
                                Edit
                            </a>
                            <button onclick="openStatusModal({{ $room->id }}, '{{ $room->name }}', '{{ $room->status }}', '{{ $room->remarks }}')" class="inline-flex items-center px-3 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition-colors duration-200">
                                <i class='bx bx-cog mr-1'></i>
                                Status
                            </button>
                        </div>
                        
                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this room?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors duration-200">
                                <i class='bx bx-trash mr-1'></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($rooms->isEmpty())
        <div class="text-center py-12">
            <i class='bx bx-building text-4xl text-gray-400 mb-4'></i>
            <p class="text-gray-500 text-lg">No meeting rooms found</p>
            <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center mt-4 text-primary hover:text-primary-dark font-medium">
                <i class='bx bx-plus mr-1'></i>
                Create your first meeting room
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Update Room Status</h3>
                <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room</label>
                    <p class="text-sm text-gray-600" id="roomName"></p>
                </div>
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="cleaning">Cleaning</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks (Optional)</label>
                    <textarea id="remarks" name="remarks" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Add any additional notes..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-md transition-colors duration-200">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(roomId, roomName, currentStatus, currentRemarks) {
    document.getElementById('roomName').textContent = roomName;
    document.getElementById('status').value = currentStatus;
    document.getElementById('remarks').value = currentRemarks || '';
    document.getElementById('statusForm').action = `/admin/rooms/${roomId}/status`;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
</script>
@endsection 