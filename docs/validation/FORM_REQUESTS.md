# Form Requests Implementation

## Overview
The application now uses Laravel Form Requests for validation instead of inline validation in controllers. This provides better organization, reusability, and maintainability of validation logic.

## Form Request Classes

### Authentication Requests

#### 1. LoginRequest (`app/Http/Requests/Auth/LoginRequest.php`)
**Purpose**: Validates login form data
- **Rules**:
  - `email`: required, email format
  - `password`: required, string
- **Custom Messages**: User-friendly error messages
- **Custom Attributes**: Human-readable field names

#### 2. RegisterRequest (`app/Http/Requests/Auth/RegisterRequest.php`)
**Purpose**: Validates user registration data
- **Rules**:
  - `name`: required, string, max 255 characters
  - `email`: required, email format, unique in users table
  - `password`: required, string, min 6 characters, confirmed
- **Custom Messages**: Clear validation error messages
- **Custom Attributes**: User-friendly field names

#### 3. UpdateProfileRequest (`app/Http/Requests/Auth/UpdateProfileRequest.php`)
**Purpose**: Validates profile update data
- **Rules**:
  - `name`: required, string, max 255 characters
  - `email`: required, email format, unique (ignoring current user)
  - `phone`: nullable, string, max 32 characters
  - `staff_id`: nullable, string, max 32 characters
  - `password`: nullable, string, min 6 characters, confirmed
- **Custom Messages**: Specific error messages for each field
- **Custom Attributes**: Human-readable field names

### Booking Requests

#### 4. StoreBookingRequest (`app/Http/Requests/Booking/StoreBookingRequest.php`)
**Purpose**: Validates new booking creation
- **Rules**:
  - `meeting_room_id`: required, exists in meeting_rooms table
  - `date`: required, date, after or equal to today
  - `start_time`: required, HH:MM format
  - `end_time`: required, HH:MM format, after start_time
  - `pic_name`: required, string, max 255 characters
  - `pic_email`: required, email format, max 255 characters
  - `pic_phone`: required, string, max 32 characters
  - `pic_staff_id`: required, string, max 32 characters
  - `meeting_title`: required, string, max 255 characters
  - `recurrence`: nullable, in: daily,weekly,monthly
  - `recurrence_end_date`: nullable, date, after or equal to date, required with recurrence
- **Custom Validation**: Business hours validation (8 AM - 6 PM)
- **Custom Messages**: Comprehensive error messages
- **Custom Attributes**: User-friendly field names

#### 5. UpdateBookingRequest (`app/Http/Requests/Booking/UpdateBookingRequest.php`)
**Purpose**: Validates booking updates
- **Rules**: Same as StoreBookingRequest (excluding recurrence fields)
- **Custom Validation**: Business hours validation
- **Custom Messages**: Specific error messages
- **Custom Attributes**: Human-readable field names

### Admin Requests

#### 6. StoreRoomRequest (`app/Http/Requests/Admin/StoreRoomRequest.php`)
**Purpose**: Validates room creation
- **Rules**:
  - `name`: required, string, max 255 characters, unique
  - `capacity`: nullable, integer, min 1, max 1000
  - `location`: nullable, string, max 255 characters
  - `description`: nullable, string, max 1000 characters
- **Custom Messages**: Clear validation messages
- **Custom Attributes**: User-friendly field names

#### 7. UpdateRoomRequest (`app/Http/Requests/Admin/UpdateRoomRequest.php`)
**Purpose**: Validates room updates
- **Rules**: Same as StoreRoomRequest (name unique ignoring current room)
- **Custom Messages**: Specific error messages
- **Custom Attributes**: Human-readable field names

#### 8. ApproveBookingRequest (`app/Http/Requests/Admin/ApproveBookingRequest.php`)
**Purpose**: Validates booking approval
- **Rules**:
  - `admin_note`: nullable, string, max 500 characters
- **Custom Messages**: Clear error messages
- **Custom Attributes**: User-friendly field names

## Benefits of Form Requests

### 1. **Separation of Concerns**
- Validation logic is separated from controller logic
- Controllers focus on business logic, not validation

### 2. **Reusability**
- Form Requests can be reused across multiple controllers
- Validation rules are centralized and consistent

### 3. **Maintainability**
- Easy to modify validation rules in one place
- Clear structure for complex validation logic

### 4. **Custom Validation**
- `withValidator()` method allows custom validation logic
- Business rules can be enforced (e.g., business hours)

### 5. **User Experience**
- Custom error messages for better user feedback
- Custom attributes for human-readable field names

### 6. **Authorization**
- `authorize()` method can handle authorization logic
- Centralized permission checking

## Implementation in Controllers

### Before (Inline Validation)
```php
public function store(Request $request)
{
    $request->validate([
        'meeting_room_id' => 'required|exists:meeting_rooms,id',
        'date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        // ... more rules
    ]);
    
    // Controller logic
}
```

### After (Form Request)
```php
public function store(StoreBookingRequest $request)
{
    // Validation is automatically handled
    // Controller logic
}
```

## Custom Validation Features

### Business Hours Validation
```php
protected function validateBusinessHours($validator)
{
    $startTime = $this->input('start_time');
    $endTime = $this->input('end_time');

    if ($startTime && $endTime) {
        $startHour = (int) substr($startTime, 0, 2);
        $endHour = (int) substr($endTime, 0, 2);

        if ($startHour < 8 || $startHour >= 18) {
            $validator->errors()->add('start_time', 'Start time must be between 8:00 AM and 6:00 PM.');
        }

        if ($endHour < 8 || $endHour > 18) {
            $validator->errors()->add('end_time', 'End time must be between 8:00 AM and 6:00 PM.');
        }
    }
}
```

### Custom Error Messages
```php
public function messages(): array
{
    return [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'Password is required.',
    ];
}
```

### Custom Attributes
```php
public function attributes(): array
{
    return [
        'email' => 'email address',
        'password' => 'password',
    ];
}
```

## File Structure
```
app/Http/Requests/
├── Auth/
│   ├── LoginRequest.php
│   ├── RegisterRequest.php
│   └── UpdateProfileRequest.php
├── Booking/
│   ├── StoreBookingRequest.php
│   └── UpdateBookingRequest.php
└── Admin/
    ├── StoreRoomRequest.php
    ├── UpdateRoomRequest.php
    └── ApproveBookingRequest.php
```

## Best Practices Implemented

1. **Consistent Naming**: All Form Requests follow Laravel naming conventions
2. **Proper Organization**: Grouped by functionality (Auth, Booking, Admin)
3. **Comprehensive Validation**: Covers all edge cases and business rules
4. **User-Friendly Messages**: Clear, actionable error messages
5. **Custom Validation**: Business logic validation (business hours)
6. **Security**: Proper authorization checks where needed
7. **Maintainability**: Easy to modify and extend validation rules

## Testing
Form Requests can be easily tested using Laravel's testing features:
- Unit tests for validation rules
- Feature tests for form submission
- Custom validation logic testing 