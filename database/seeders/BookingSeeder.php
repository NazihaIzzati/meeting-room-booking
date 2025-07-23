<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\MeetingRoom;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and rooms
        $users = User::all();
        $rooms = MeetingRoom::all();

        if ($users->isEmpty() || $rooms->isEmpty()) {
            return;
        }

        // Sample booking data for today and upcoming days
        $bookings = [
            // Today's bookings
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Conference Room A')->first()->id,
                'date' => Carbon::today(),
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'pic_name' => 'John Smith',
                'pic_email' => 'john.smith@company.com',
                'pic_phone' => '+1234567890',
                'pic_staff_id' => 'EMP001',
                'meeting_title' => 'Weekly Team Meeting',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Board Room')->first()->id,
                'date' => Carbon::today(),
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
                'pic_name' => 'Sarah Johnson',
                'pic_email' => 'sarah.johnson@company.com',
                'pic_phone' => '+1234567891',
                'pic_staff_id' => 'EMP002',
                'meeting_title' => 'Executive Review',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Training Room')->first()->id,
                'date' => Carbon::today(),
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'pic_name' => 'Mike Davis',
                'pic_email' => 'mike.davis@company.com',
                'pic_phone' => '+1234567892',
                'pic_staff_id' => 'EMP003',
                'meeting_title' => 'New Employee Training',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Client Meeting Room')->first()->id,
                'date' => Carbon::today(),
                'start_time' => '15:30:00',
                'end_time' => '16:30:00',
                'pic_name' => 'Lisa Wilson',
                'pic_email' => 'lisa.wilson@company.com',
                'pic_phone' => '+1234567893',
                'pic_staff_id' => 'EMP004',
                'meeting_title' => 'Client Presentation',
                'status' => 'approved'
            ],

            // Tomorrow's bookings
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Conference Room B')->first()->id,
                'date' => Carbon::tomorrow(),
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'pic_name' => 'David Brown',
                'pic_email' => 'david.brown@company.com',
                'pic_phone' => '+1234567894',
                'pic_staff_id' => 'EMP005',
                'meeting_title' => 'Project Planning',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Innovation Lab')->first()->id,
                'date' => Carbon::tomorrow(),
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'pic_name' => 'Emma Taylor',
                'pic_email' => 'emma.taylor@company.com',
                'pic_phone' => '+1234567895',
                'pic_staff_id' => 'EMP006',
                'meeting_title' => 'Product Brainstorming',
                'status' => 'approved'
            ],

            // Day after tomorrow
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Meeting Room 1')->first()->id,
                'date' => Carbon::today()->addDays(2),
                'start_time' => '09:30:00',
                'end_time' => '10:30:00',
                'pic_name' => 'Alex Chen',
                'pic_email' => 'alex.chen@company.com',
                'pic_phone' => '+1234567896',
                'pic_staff_id' => 'EMP007',
                'meeting_title' => 'Interview Session',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Conference Room A')->first()->id,
                'date' => Carbon::today()->addDays(2),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'pic_name' => 'Rachel Green',
                'pic_email' => 'rachel.green@company.com',
                'pic_phone' => '+1234567897',
                'pic_staff_id' => 'EMP008',
                'meeting_title' => 'Quarterly Review',
                'status' => 'approved'
            ],

            // This week
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Training Room')->first()->id,
                'date' => Carbon::today()->addDays(3),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'pic_name' => 'Tom Anderson',
                'pic_email' => 'tom.anderson@company.com',
                'pic_phone' => '+1234567898',
                'pic_staff_id' => 'EMP009',
                'meeting_title' => 'Software Training',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $rooms->where('name', 'Board Room')->first()->id,
                'date' => Carbon::today()->addDays(4),
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'pic_name' => 'Jennifer Lee',
                'pic_email' => 'jennifer.lee@company.com',
                'pic_phone' => '+1234567899',
                'pic_staff_id' => 'EMP010',
                'meeting_title' => 'Board Meeting',
                'status' => 'approved'
            ]
        ];

        // Add a guest booking (user_id = null)
        $bookings[] = [
            'user_id' => null,
            'meeting_room_id' => $rooms->first()->id,
            'date' => Carbon::today()->addDays(5),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'pic_name' => 'Guest User',
            'pic_email' => 'guest@example.com',
            'pic_phone' => '+1234567800',
            'pic_staff_id' => 'GUEST01',
            'meeting_title' => 'Guest Booking',
            'status' => 'approved'
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
