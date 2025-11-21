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
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b-4 border-primary">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('landing') }}" class="flex items-center">
                        <img src="{{ asset('assests/image/Logo_BMMB_Full.png') }}" alt="Meeting Room Booking" class="h-12 w-auto">
                    </a>
                </div>
                
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <a href="{{ route('booking.lookup.form') }}" class="hidden sm:inline-flex text-sm font-semibold text-gray-600 hover:text-primary transition">
                        Find Booking
                    </a>
                    <a href="{{ route('bookings.create') }}" class="px-4 py-2 rounded-full bg-primary text-white text-sm font-semibold shadow hover:bg-primary-dark transition">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="w-full flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white">
        <div class="bg-gray-50 border-t border-gray-100 py-4 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Meeting Room Booking. All rights reserved.
        </div>
    </footer>

    @if(session('success') || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: @json(session('success')),
                        confirmButtonColor: '#FE8000',
                        timer: 3500,
                        showConfirmButton: false
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: @json(session('error')),
                        confirmButtonColor: '#FE8000'
                    });
                @endif
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html> 