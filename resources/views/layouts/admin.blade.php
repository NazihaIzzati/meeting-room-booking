<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Meeting Room Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-primary text-white">
                <div class="flex items-center space-x-2">
                    <i class='bx bx-calendar-check text-2xl'></i>
                    <span class="text-xl font-bold">Admin Panel</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->is('admin/dashboard') ? 'bg-primary text-white' : 'text-gray-700' }}">
                    <i class='bx bx-home-alt text-xl mr-3'></i>
                    <span>Dashboard</span>
                </a>

                <a href="/admin/rooms" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->is('admin/rooms*') ? 'bg-primary text-white' : 'text-gray-700' }}">
                    <i class='bx bx-building text-xl mr-3'></i>
                    <span>Meeting Rooms</span>
                </a>

                <a href="/admin/bookings" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->is('admin/bookings*') ? 'bg-primary text-white' : 'text-gray-700' }}">
                    <i class='bx bx-calendar text-xl mr-3'></i>
                    <span>All Bookings</span>
                </a>


                <a href="/admin/audit-logs" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->is('admin/audit-logs*') ? 'bg-primary text-white' : 'text-gray-700' }}">
                    <i class='bx bx-history text-xl mr-3'></i>
                    <span>Audit Logs</span>
                </a>

                <a href="/my-bookings" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->is('my-bookings*') ? 'bg-primary text-white' : 'text-gray-700' }}">
                    <i class='bx bx-calendar-check text-xl mr-3'></i>
                    <span>My Bookings</span>
                </a>

                <a href="/availability" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors duration-200 {{ request()->is('availability*') ? 'bg-primary text-white' : 'text-gray-700' }}">
                    <i class='bx bx-time-five text-xl mr-3'></i>
                    <span>Room Availability</span>
                </a>
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                        <i class='bx bx-user text-white text-xl'></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('admin.profile.show') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <i class='bx bx-user mr-2'></i>
                        <span>Profile</span>
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" id="sidebar-logout-form" class="block">
                        @csrf
                        <button type="button" class="w-full flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                            onclick="confirmLogout('sidebar-logout-form')">
                            <i class='bx bx-log-out mr-2'></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class='bx bx-menu text-xl text-gray-600'></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Admin Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <i class='bx bx-bell text-xl text-gray-600'></i>
                            </button>
                        </div>

                        <!-- Search -->
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('admin.logout') }}" id="topbar-logout-form">
                            @csrf
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary-dark transition"
                                onclick="confirmLogout('topbar-logout-form')">
                                <i class='bx bx-log-out mr-2 text-lg'></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class='bx bx-check-circle text-xl'></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class='bx bx-error-circle text-xl'></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg flex items-center space-x-2">
                        <i class='bx bx-error text-xl'></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden hidden" id="sidebar-overlay"></div>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.querySelector('button[class*="lg:hidden"]');
            const overlay = document.getElementById('sidebar-overlay');
            const sidebar = document.querySelector('aside');

            if (menuButton && overlay && sidebar) {
                menuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
        });

        // Logout confirmation
        window.confirmLogout = function(formId) {
            if (confirm('Are you sure you want to log out?')) {
                document.getElementById(formId).submit();
            }
        };
    </script>
</body>
</html> 