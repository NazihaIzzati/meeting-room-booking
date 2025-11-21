<?php

namespace Database\Seeders;

use App\Models\MeetingRoom;
use Illuminate\Database\Seeder;

class MeetingRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meetingRooms = [
            [
                'name' => 'ByteSpace',
                'location' => 'Level 8',
                'capacity' => 10,
                'description' => 'Modern meeting space with cutting-edge technology and comfortable seating. Perfect for collaborative sessions and team discussions. Features high-speed internet, smart displays, and flexible furniture arrangements.',
                'status' => 'available',
                'remarks' => null
            ],
            [
                'name' => 'The Hub',
                'location' => 'Level 8',
                'capacity' => 10,
                'description' => 'Central meeting hub designed for dynamic team interactions and brainstorming sessions. Equipped with interactive whiteboards, video conferencing capabilities, and ergonomic seating for productive meetings.',
                'status' => 'available',
                'remarks' => null
            ],
            [
                'name' => 'Cache Corner',
                'location' => 'Level 8',
                'capacity' => 4,
                'description' => 'Intimate meeting space perfect for focused discussions and small team collaborations. Features a cozy atmosphere with modern amenities, ideal for quick meetings and one-on-one sessions.',
                'status' => 'available',
                'remarks' => null
            ],
            [
                'name' => '404 Den',
                'location' => 'Level 8',
                'capacity' => 4,
                'description' => 'Quiet and secluded meeting room for confidential discussions and focused work sessions. Provides a private environment with soundproofing and premium audio-visual equipment.',
                'status' => 'maintenance',
                'remarks' => 'Audio system upgrade in progress. Expected completion: End of week.'
            ],
            [
                'name' => 'Ping Point',
                'location' => 'Level 8',
                'capacity' => 10,
                'description' => 'High-tech meeting room with advanced communication tools and collaborative features. Perfect for remote team meetings, client presentations, and interactive workshops with seamless connectivity.',
                'status' => 'available',
                'remarks' => null
            ],
            [
                'name' => 'Loop Lounge',
                'location' => 'Level 8',
                'capacity' => 10,
                'description' => 'Versatile meeting space with flexible seating arrangements and modern presentation equipment. Ideal for creative sessions, team building activities, and collaborative project work.',
                'status' => 'cleaning',
                'remarks' => 'Scheduled cleaning. Will be available in 30 minutes.'
            ]
        ];

        foreach ($meetingRooms as $room) {
            MeetingRoom::updateOrCreate(
                ['name' => $room['name']],
                $room
            );
        }
    }
}
