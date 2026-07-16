<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.bookings.index', [
            'bookings' => Booking::with(['user', 'flight.departureAirport', 'flight.arrivalAirport'])
                ->when($request->status, fn ($query, $status) => $query->where('status', $status))
                ->when($request->flight_id, fn ($query, $flight) => $query->where('flight_id', $flight))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'flights' => Flight::orderByDesc('departure_time')->get(['id', 'flight_number']),
        ]);
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        DB::transaction(function () use ($booking) {
            $flight = Flight::lockForUpdate()->findOrFail($booking->flight_id);
            $booking = Booking::lockForUpdate()->findOrFail($booking->id);

            if ($booking->status === 'confirmed') {
                $booking->update(['status' => 'cancelled']);
                $flight->increment('available_seats', $booking->seats_booked);
            }
        }, 3);

        return back()->with('success', 'Booking cancelled by admin.');
    }
}
