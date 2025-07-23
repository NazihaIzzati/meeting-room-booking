<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Meeting Room Booking')</title>

    <!-- Tailwind Config Script (requires Tailwind CSS) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind Config Script -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Poppins', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: '#FE8000',
                        'primary-dark': '#E67300',
                        'primary-light': '#FF9933'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b-4 border-primary">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center space-x-2">
                        <i class='bx bx-calendar-check text-2xl text-primary'></i>
                        <span class="text-xl font-bold text-gray-900">Meeting Room Booking</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-primary transition-colors duration-200 flex items-center space-x-1">
                            <i class='bx bx-home-alt'></i>
                            <span class="hidden sm:inline">Dashboard</span>
                        </a>
                        @endif
                        <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-primary transition-colors duration-200 flex items-center space-x-1">
                            <i class='bx bx-calendar'></i>
                            <span class="hidden sm:inline">My Bookings</span>
                        </a>
                        <a href="{{ route('availability.index') }}" class="text-gray-700 hover:text-primary transition-colors duration-200 flex items-center space-x-1">
                            <i class='bx bx-time'></i>
                            <span class="hidden sm:inline">Availability</span>
                        </a>
                        <div class="relative group">
                            <button class="flex items-center space-x-1 sm:space-x-2 text-gray-700 hover:text-primary transition-colors duration-200">
                                <i class='bx bx-user-circle text-lg sm:text-xl'></i>
                                <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                <i class='bx bx-chevron-down hidden sm:inline'></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2">
                                    <i class='bx bx-user'></i>
                                    <span>Profile</span>
                                </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center space-x-2">
                                        <i class='bx bx-log-out'></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="w-full flex-1">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center space-x-2">
                <i class='bx bx-check-circle'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex items-center space-x-2">
                <i class='bx bx-error-circle'></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white">
        <div class="bg-gray-50 border-t border-gray-100 py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Meeting Room Booking. All rights reserved.
        </div>
    </footer>
    @stack('scripts')
</body>
</html> 