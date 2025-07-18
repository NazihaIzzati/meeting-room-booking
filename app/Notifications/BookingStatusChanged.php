<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Booking;

class BookingStatusChanged extends Notification implements ShouldQueue
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
        $status = ucfirst($this->booking->status);
        return (new MailMessage)
            ->subject("Your booking has been $status")
            ->greeting("Hello {$this->booking->pic_name},")
            ->line("Your meeting room booking status has changed to: **$status**.")
            ->line('Meeting Title: ' . $this->booking->meeting_title)
            ->line('Room: ' . ($this->booking->meetingRoom->name ?? '-'))
            ->line('Date: ' . $this->booking->date)
            ->line('Time: ' . $this->booking->start_time . ' - ' . $this->booking->end_time)
            ->line('If you have any questions, please contact the admin.')
            ->salutation('Thank you for using our meeting room booking system!');
    }
}
