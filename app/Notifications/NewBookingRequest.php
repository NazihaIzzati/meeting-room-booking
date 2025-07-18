<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Booking;

class NewBookingRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Meeting Room Booking Request')
            ->greeting('Hello Admin,')
            ->line('A new meeting room booking has been requested and is pending your approval:')
            ->line('Meeting Title: ' . $this->booking->meeting_title)
            ->line('Room: ' . ($this->booking->meetingRoom->name ?? '-'))
            ->line('Date: ' . $this->booking->date)
            ->line('Time: ' . $this->booking->start_time . ' - ' . $this->booking->end_time)
            ->line('PIC: ' . $this->booking->pic_name . ' (' . $this->booking->pic_email . ')')
            ->action('Approve Booking', url('/admin/bookings/pending'))
            ->salutation('Thank you!');
    }
} 