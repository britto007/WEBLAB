<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        return view('bookings.index', [
            'bookings' => Booking::with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport'])
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(10),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = DB::transaction(function () use ($request) {
            $flight = Flight::lockForUpdate()->findOrFail($request->integer('flight_id'));
            $seats = $request->integer('seats_booked');

            if ($flight->status !== 'scheduled' || $flight->departure_time->isPast()) {
                throw ValidationException::withMessages(['flight_id' => 'This flight is not open for booking.']);
            }

            if ($seats > $flight->available_seats) {
                throw ValidationException::withMessages(['seats_booked' => 'Only '.$flight->available_seats.' seat(s) are available.']);
            }

            $flight->decrement('available_seats', $seats);

            return Booking::create([
                'user_id' => $request->user()->id,
                'flight_id' => $flight->id,
                'booking_reference' => $this->makeReference(),
                'seats_booked' => $seats,
                'total_price' => $flight->price * $seats,
                'status' => 'confirmed',
            ]);
        });

        return redirect()->route('bookings.index')
            ->with('success', 'Booking confirmed. Reference: '.$booking->booking_reference);
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        DB::transaction(function () use ($booking) {
            $flight = Flight::lockForUpdate()->findOrFail($booking->flight_id);
            $lockedBooking = Booking::lockForUpdate()->findOrFail($booking->id);

            if ($lockedBooking->status === 'confirmed') {
                $lockedBooking->update(['status' => 'cancelled']);
                $flight->increment('available_seats', $lockedBooking->seats_booked);
            }
        }, 3);

        return back()->with('success', 'Your booking was cancelled and the seats were returned.');
    }

    private function makeReference(): string
    {
        do {
            $reference = 'FMS'.strtoupper(Str::random(7));
        } while (Booking::where('booking_reference', $reference)->exists());

        return $reference;
    }
}
