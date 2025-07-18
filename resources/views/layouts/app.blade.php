<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meeting Room Booking</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-[#1b1b18] text-white px-6 py-3 flex justify-between items-center">
        <div class="font-bold text-lg">
            <a href="/dashboard">Meeting Room Booking</a>
        </div>
        <div class="space-x-4">
            @auth
                <a href="/dashboard" class="hover:underline">Dashboard</a>
                <a href="/my-bookings" class="hover:underline">My Bookings</a>
                <a href="/availability" class="hover:underline">Room Availability</a>
                <a href="/profile" class="hover:underline">Profile</a>
                @if(auth()->user()->isAdmin())
                    <a href="/admin/rooms" class="hover:underline">Admin Rooms</a>
                    <a href="/admin/bookings" class="hover:underline">Admin Bookings</a>
                    @php $pendingCount = \App\Models\Booking::where('status', 'pending')->count(); @endphp
                    <div class="relative inline-block align-middle">
                        <a href="/admin/bookings/pending" class="inline-block">
                            <img src="https://cdn-icons-png.flaticon.com/512/1827/1827392.png" alt="Notifications" class="h-6 w-6 inline">
                            @if($pendingCount > 0)
                                <span class="absolute -top-1 -right-1 flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-white text-xs font-bold border-2 border-white">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </div>
                @endif
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded bg-primary text-white shadow hover:bg-primary/90 focus:ring-2 focus:ring-primary transition-all">Logout</button>
                </form>
            @else
                <a href="/login" class="hover:underline">Login</a>
                <a href="/register" class="hover:underline">Register</a>
            @endauth
        </div>
    </nav>
    <main class="py-6">
        @yield('content')
    </main>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html> 