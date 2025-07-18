<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Room Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico">
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col py-8 px-4">
            <div class="flex items-center mb-10">
                <span class="text-2xl font-bold text-blue-600">Meeting</span><span class="text-2xl font-light ml-1">Room</span>
            </div>
            <nav class="flex-1 space-y-2">
                <a href="/dashboard" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 transition {{ request()->is('dashboard') ? 'bg-blue-100 text-blue-600 font-semibold' : 'text-gray-700' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0H7m6 0v6m0 0H7m6 0h6"/></svg>
                    Home
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 transition text-gray-700">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Meeting
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 transition text-gray-700">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Employee
                </a>
                <a href="#" class="flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 transition text-gray-700">
                    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Schedule
                </a>
            </nav>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="flex items-center justify-between bg-white shadow px-8 py-4">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden p-2 rounded hover:bg-blue-50">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <span class="text-xl font-bold text-blue-600 hidden lg:block">MeetingRoom</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 p-2 rounded hover:bg-blue-50 focus:outline-none">
                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?s=80&d=identicon" alt="Avatar" class="h-10 w-10 rounded-full border-2 border-blue-200">
                            <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg opacity-0 group-hover:opacity-100 transition pointer-events-none group-hover:pointer-events-auto z-50">
                            <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Profile</a>
                            <form method="POST" action="/logout" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 flex items-center gap-2 text-red-600 hover:bg-red-50 hover:text-red-700 rounded shadow transition-all">
                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/></svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </header>
            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html> 