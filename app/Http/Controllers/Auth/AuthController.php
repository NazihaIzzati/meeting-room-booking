<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form (READ operation)
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Process login (CREATE operation)
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Handle redirect parameters
            $redirect = $request->get('redirect');
            $roomId = $request->get('room');
            
            if ($redirect === 'booking') {
                if ($roomId) {
                    return redirect('/bookings/create?room=' . $roomId);
                } else {
                    return redirect('/bookings/create');
                }
            } elseif ($redirect === 'availability') {
                if ($roomId) {
                    return redirect('/availability?room=' . $roomId);
                } else {
                    return redirect('/availability');
                }
            }
            
            return redirect()->intended('/dashboard');
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput();
    }

    /**
     * Show the registration form (READ operation)
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Process registration (CREATE operation)
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        
        // Handle redirect parameters
        $redirect = $request->get('redirect');
        $roomId = $request->get('room');
        
        if ($redirect === 'booking') {
            if ($roomId) {
                return redirect('/bookings/create?room=' . $roomId);
            } else {
                return redirect('/bookings/create');
            }
        } elseif ($redirect === 'availability') {
            if ($roomId) {
                return redirect('/availability?room=' . $roomId);
            } else {
                return redirect('/availability');
            }
        }
        
        return redirect('/dashboard');
    }

    /**
     * Process logout (DELETE operation)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show user profile (READ operation)
     *
     * @return \Illuminate\View\View
     */
    public function showProfile()
    {
        return view('admin.profile.index', ['user' => Auth::user()]);
    }

    /**
     * Update user profile (UPDATE operation)
     *
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->only('name', 'email'));
        
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
