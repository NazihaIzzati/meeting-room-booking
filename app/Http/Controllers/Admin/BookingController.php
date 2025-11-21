<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveBookingRequest;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\MeetingRoom;
use App\Notifications\BookingStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of all bookings (READ operation)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = Booking::with('meetingRoom', 'user')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }


    /**
     * Display the specified booking (READ operation)
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function show(Booking $booking)
    {
        $booking->load(['meetingRoom', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for approving a booking (READ operation - form)
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function approve(Booking $booking)
    {
        $booking->load(['meetingRoom', 'user']);
        return view('admin.bookings.approve', compact('booking'));
    }

    /**
     * Approve the specified booking (UPDATE operation)
     *
     * @param ApproveBookingRequest $request
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function approveBooking(ApproveBookingRequest $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending bookings can be approved.');
        }

        $booking->status = 'approved';
        $booking->save();

        $note = $request->input('admin_note');
        $details = 'Booking approved';
        if ($note) {
            $details .= ' | Note: ' . $note;
        }

        AuditLog::create([
            'booking_id' => $booking->id, 
            'user_id' => Auth::id(), 
            'action' => 'approved', 
            'details' => $details
        ]);

        if ($booking->user && $booking->user->email) {
            $booking->user->notify(new BookingStatusChanged($booking));
        }

        $booking->load('meetingRoom');
        return view('admin.bookings.approve-success', compact('booking'));
    }

    /**
     * Reject the specified booking (UPDATE operation)
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return response()->json(['error' => 'Only pending bookings can be rejected.'], 400);
        }

        $booking->status = 'cancelled';
        $booking->save();

        $note = $request->input('admin_note');
        $details = 'Booking cancelled';
        if ($note) {
            $details .= ' | Note: ' . $note;
        }

        AuditLog::create([
            'booking_id' => $booking->id, 
            'user_id' => Auth::id(), 
            'action' => 'cancelled', 
            'details' => $details
        ]);

        if ($booking->user && $booking->user->email) {
            $booking->user->notify(new BookingStatusChanged($booking));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified booking (DELETE operation)
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        AuditLog::create([
            'booking_id' => $booking->id, 
            'user_id' => Auth::id(), 
            'action' => 'cancelled', 
            'details' => 'Booking cancelled'
        ]);

        return redirect('/admin/bookings')->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Export bookings to CSV (READ operation for export)
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $bookings = Booking::with(['meetingRoom', 'user'])->orderBy('date')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings.csv"',
        ];

        $columns = [
            'ID', 'Date', 'Start Time', 'End Time', 'Room', 'Meeting Title', 'PIC Name', 
            'PIC Email', 'PIC Phone', 'PIC Staff ID', 'User', 'Status', 'Recurrence', 
            'Recurrence End', 'Created At', 'Updated At'
        ];

        $callback = function() use ($bookings, $columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);
            
            foreach ($bookings as $b) {
                fputcsv($out, [
                    $b->id,
                    $b->date,
                    $b->start_time,
                    $b->end_time,
                    $b->meetingRoom->name ?? '',
                    $b->meeting_title,
                    $b->pic_name,
                    $b->pic_email,
                    $b->pic_phone,
                    $b->pic_staff_id,
                    $b->user->name ?? '',
                    $b->status,
                    $b->recurrence,
                    $b->recurrence_end_date,
                    $b->created_at,
                    $b->updated_at,
                ]);
            }
            
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
