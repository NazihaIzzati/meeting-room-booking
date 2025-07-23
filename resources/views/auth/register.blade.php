@extends('layouts.public')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center mb-6 shadow-lg">
                <i class='bx bx-user-plus text-white text-4xl'></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Create your account</h2>
            <p class="text-gray-600">
                Join us to start booking meeting rooms
            </p>
        </div>
        
        <div class="bg-white py-8 px-8 shadow-xl rounded-xl border border-gray-100">
            <form method="POST" action="/register" class="space-y-6">
                @csrf
                
                <!-- Hidden fields for redirect parameters -->
                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif
                @if(request()->has('room'))
                    <input type="hidden" name="room" value="{{ request('room') }}">
                @endif
                
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-user mr-2 text-primary'></i>Full Name
                    </label>
                    <input 
                        id="name"
                        type="text" 
                        name="name" 
                        required 
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 @error('name') border-red-500 ring-red-200 @enderror" 
                        value="{{ old('name') }}"
                        placeholder="Enter your full name"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-envelope mr-2 text-primary'></i>Email Address
                    </label>
                    <input 
                        id="email"
                        type="email" 
                        name="email" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 @error('email') border-red-500 ring-red-200 @enderror" 
                        value="{{ old('email') }}"
                        placeholder="Enter your email address"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-lock-alt mr-2 text-primary'></i>Password
                    </label>
                    <input 
                        id="password"
                        type="password" 
                        name="password" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 @error('password') border-red-500 ring-red-200 @enderror"
                        placeholder="Create a strong password"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-lock mr-2 text-primary'></i>Confirm Password
                    </label>
                    <input 
                        id="password_confirmation"
                        type="password" 
                        name="password_confirmation" 
                        required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                        placeholder="Confirm your password"
                    >
                </div>

                <div class="flex items-start">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mt-1"
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the 
                        <a href="#" class="text-primary hover:text-primary-dark transition-colors duration-200 font-medium">Terms of Service</a> 
                        and 
                        <a href="#" class="text-primary hover:text-primary-dark transition-colors duration-200 font-medium">Privacy Policy</a>
                    </label>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start space-x-2">
                        <i class='bx bx-error-circle text-xl mt-0.5'></i>
                        <div>
                            <p class="font-medium">Please fix the following errors:</p>
                            <ul class="mt-1 text-sm list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <button 
                    type="submit" 
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:scale-[1.02]"
                >
                    <i class='bx bx-user-plus mr-2'></i>
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="/login" class="font-semibold text-primary hover:text-primary-dark transition-colors duration-200">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 