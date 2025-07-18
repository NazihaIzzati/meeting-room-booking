<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Room Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FE5000',
                        dark: '#1b1b18',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    <link rel="icon" href="/favicon.ico">
</head>
<body class="bg-gray-50 min-h-screen font-sans" x-data="{ showLogoutConfirm: false }">
    @auth
    <nav class="bg-white border border-gray-200 rounded-xl mx-2 mt-2 shadow flex flex-col">
        <div class="flex items-center justify-between px-8 py-4">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <a href="/dashboard" class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 48 48"><path fill="#FE5000" d="M24 4c11.046 0 20 8.954 20 20s-8.954 20-20 20S4 35.046 4 24 12.954 4 24 4Z"/></svg>
                </a>
            </div>
            <!-- Right: Notification + Avatar + Logout -->
            <div class="flex items-center gap-6">
                <button class="relative text-gray-400 hover:text-primary focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email ?? '')) }}?s=40&d=identicon" alt="Avatar" class="h-10 w-10 rounded-full border-2 border-primary object-cover" />
                <form method="POST" action="/logout" class="ml-2" @submit.prevent="showLogoutConfirm = true">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-red-50 hover:text-red-600 transition group relative text-gray-700 font-semibold" title="Logout">
                        <svg class="h-5 w-5 text-red-500 group-hover:text-red-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
        <!-- Logout Confirmation Modal -->
        <div x-show="showLogoutConfirm" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" style="display: none;">
            <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-sm mx-auto flex flex-col items-center">
                <svg class="h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                <div class="text-lg font-semibold mb-2">Confirm Logout</div>
                <div class="text-gray-600 mb-6 text-center">Are you sure you want to log out?</div>
                <div class="flex gap-4 w-full">
                    <button @click="showLogoutConfirm = false" class="flex-1 px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 transition">Cancel</button>
                    <form method="POST" action="/logout" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Horizontal Nav -->
        <div class="flex items-center gap-2 px-8 border-t border-gray-100">
            <a href="/dashboard" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-900 bg-gray-100">Dashboard</a>
            <a href="/my-bookings" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-700 hover:bg-gray-100">My Bookings</a>
            <a href="/availability" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-700 hover:bg-gray-100">Room Availability</a>
            <a href="/profile" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-700 hover:bg-gray-100">Profile</a>
            @if(auth()->user() && auth()->user()->isAdmin())
                <a href="/admin/rooms" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-700 hover:bg-gray-100">Admin Rooms</a>
                <a href="/admin/bookings" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-700 hover:bg-gray-100">Admin Bookings</a>
                <a href="/admin/bookings/pending" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-700 hover:bg-gray-100">Pending</a>
                <a href="/admin/audit-logs" class="px-4 py-2 font-semibold rounded-lg mt-2 mb-1 transition text-gray-700 hover:bg-gray-100">Audit Log</a>
            @endif
        </div>
    </nav>
    @endauth
    <main class="py-8 max-w-5xl mx-auto px-4">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html> 