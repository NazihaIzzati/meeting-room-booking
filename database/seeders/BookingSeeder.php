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

        // Get available rooms (filter by status = 'available')
        $availableRooms = $rooms->where('status', 'available');
        
        if ($availableRooms->isEmpty()) {
            return; // No available rooms to create bookings for
        }

        // Sample booking data for today and upcoming days
        $bookings = [
            // Today's bookings
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->first()->id,
                'date' => Carbon::today(),
                'start_time' => '09:00:00',
                'end_time' => '10:30:00',
                'pic_name' => 'John Smith',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP001',
                'meeting_title' => 'Weekly Team Meeting',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->skip(1)->first()->id ?? $availableRooms->first()->id,
                'date' => Carbon::today(),
                'start_time' => '11:00:00',
                'end_time' => '12:00:00',
                'pic_name' => 'Sarah Johnson',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP002',
                'meeting_title' => 'Executive Review',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->skip(2)->first()->id ?? $availableRooms->first()->id,
                'date' => Carbon::today(),
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'pic_name' => 'Mike Davis',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP003',
                'meeting_title' => 'New Employee Training',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->skip(3)->first()->id ?? $availableRooms->first()->id,
                'date' => Carbon::today(),
                'start_time' => '15:30:00',
                'end_time' => '16:30:00',
                'pic_name' => 'Lisa Wilson',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP004',
                'meeting_title' => 'Client Presentation',
                'status' => 'approved'
            ],

            // Tomorrow's bookings
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->skip(4)->first()->id ?? $availableRooms->random()->id,
                'date' => Carbon::tomorrow(),
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'pic_name' => 'David Brown',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP005',
                'meeting_title' => 'Project Planning',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->random()->id,
                'date' => Carbon::tomorrow(),
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'pic_name' => 'Emma Taylor',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP006',
                'meeting_title' => 'Product Brainstorming',
                'status' => 'approved'
            ],

            // Day after tomorrow
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->random()->id,
                'date' => Carbon::today()->addDays(2),
                'start_time' => '09:30:00',
                'end_time' => '10:30:00',
                'pic_name' => 'Alex Chen',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP007',
                'meeting_title' => 'Interview Session',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->random()->id,
                'date' => Carbon::today()->addDays(2),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'pic_name' => 'Rachel Green',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP008',
                'meeting_title' => 'Quarterly Review',
                'status' => 'approved'
            ],

            // This week
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->random()->id,
                'date' => Carbon::today()->addDays(3),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'pic_name' => 'Tom Anderson',
                'pic_email' => null,
                'pic_phone' => null,
                'pic_staff_id' => 'EMP009',
                'meeting_title' => 'Software Training',
                'status' => 'approved'
            ],
            [
                'user_id' => $users->first()->id,
                'meeting_room_id' => $availableRooms->random()->id,
                'date' => Carbon::today()->addDays(4),
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'pic_name' => 'Jennifer Lee',
                'pic_email' => null,
                'pic_phone' => null,
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
            'pic_email' => null,
            'pic_phone' => null,
            'pic_staff_id' => 'GUEST01',
            'meeting_title' => 'Guest Booking',
            'status' => 'approved'
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
