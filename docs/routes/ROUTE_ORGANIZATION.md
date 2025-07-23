# Route Organization Documentation

## Overview
The routes have been reorganized from closure-based routes to controller-based routes for better maintainability, testability, and organization.

## Controller Structure

### 1. PublicController (`app/Http/Controllers/PublicController.php`)
**Purpose**: Handles public pages that don't require authentication
- `welcome()` - Home page
- `dashboard()` - Main dashboard (requires auth)
- `availability()` - Room availability view (requires auth)

### 2. AuthController (`app/Http/Controllers/AuthController.php`)
**Purpose**: Handles authentication and user profile management
- `showLogin()` - Display login form
- `login()` - Process login
- `showRegister()` - Display registration form
- `register()` - Process registration
- `logout()` - Process logout
- `showProfile()` - Display user profile
- `updateProfile()` - Update user profile

### 3. BookingController (`app/Http/Controllers/BookingController.php`)
**Purpose**: Handles booking operations for regular users
- `myBookings()` - View user's bookings
- `store()` - Create new booking
- `edit()` - Show edit booking form
- `update()` - Update booking
- `destroy()` - Cancel booking (AJAX)
- `series()` - View recurring booking series
- `cancelSeries()` - Cancel recurring series
- `getByDate()` - Get bookings by date (AJAX)

### 4. Admin Controllers

#### RoomController (`app/Http/Controllers/Admin/RoomController.php`)
**Purpose**: Handles room management for admins
- `index()` - List all rooms
- `create()` - Show create room form
- `store()` - Create new room
- `edit()` - Show edit room form
- `update()` - Update room
- `destroy()` - Delete room

#### BookingController (`app/Http/Controllers/Admin/BookingController.php`)
**Purpose**: Handles booking management for admins
- `index()` - List all bookings
- `pending()` - List pending bookings with filters
- `approve()` - Show approval form
- `approveBooking()` - Approve booking
- `reject()` - Reject booking (AJAX)
- `destroy()` - Cancel booking
- `export()` - Export bookings to CSV

#### AuditController (`app/Http/Controllers/Admin/AuditController.php`)
**Purpose**: Handles audit log viewing for admins
- `index()` - List audit logs

## Route Groups

### Public Routes
```php
Route::get('/', [PublicController::class, 'welcome']);
Route::get('/dashboard', [PublicController::class, 'dashboard'])->middleware('auth');
Route::get('/availability', [PublicController::class, 'availability'])->middleware('auth');
```

### Authentication Routes
```php
Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/profile', [AuthController::class, 'showProfile'])->middleware('auth');
Route::post('/profile', [AuthController::class, 'updateProfile'])->middleware('auth');
```

### Booking Routes
```php
Route::get('/my-bookings', [BookingController::class, 'myBookings'])->middleware('auth');
Route::post('/book', [BookingController::class, 'store'])->middleware('auth');
Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->middleware('auth');
Route::post('/bookings/{booking}/edit', [BookingController::class, 'update'])->middleware('auth');
Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->middleware('auth');
Route::get('/bookings/series/{parent}', [BookingController::class, 'series'])->middleware('auth');
Route::post('/bookings/series/{parent}/cancel', [BookingController::class, 'cancelSeries'])->middleware('auth');
Route::get('/bookings/by-date', [BookingController::class, 'getByDate'])->middleware('auth');
```

### Admin Routes
```php
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Room Management
    Route::resource('rooms', RoomController::class);
    
    // Booking Management
    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::get('/bookings/pending', [AdminBookingController::class, 'pending']);
    Route::get('/bookings/export', [AdminBookingController::class, 'export']);
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy']);
    
    // Audit Logs
    Route::get('/audit-logs', [AuditController::class, 'index']);
});
```

### Admin Booking Approval Routes
```php
Route::get('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->middleware(['auth', 'admin']);
Route::post('/bookings/{booking}/approve', [AdminBookingController::class, 'approveBooking'])->middleware(['auth', 'admin']);
Route::post('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->middleware(['auth', 'admin']);
```

## Middleware

### AdminMiddleware (`app/Http/Middleware/AdminMiddleware.php`)
- Checks if user is authenticated and is an admin
- Registered as 'admin' alias in `bootstrap/app.php`
- Applied to all admin routes

## Benefits of This Organization

1. **Separation of Concerns**: Each controller has a specific responsibility
2. **Maintainability**: Easier to find and modify specific functionality
3. **Testability**: Controllers can be unit tested independently
4. **Code Reusability**: Controller methods can be reused
5. **Security**: Admin middleware ensures proper authorization
6. **Scalability**: Easy to add new features to appropriate controllers
7. **Documentation**: Clear structure makes the codebase self-documenting

## File Structure
```
app/Http/Controllers/
├── PublicController.php
├── AuthController.php
├── BookingController.php
└── Admin/
    ├── RoomController.php
    ├── BookingController.php
    └── AuditController.php

app/Http/Middleware/
└── AdminMiddleware.php

routes/
└── web.php (organized routes)
``` 