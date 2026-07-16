<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFlightRequest;
use App\Http\Requests\UpdateFlightRequest;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function index(Request $request): View
    {
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->withCount('bookings')
            ->when($request->airline, fn ($query, $airline) => $query->where('airline_id', $airline))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->date, fn ($query, $date) => $query->whereDate('departure_time', $date))
            ->orderByDesc('departure_time')
            ->paginate(12)
            ->withQueryString();

        return view('admin.flights.index', [
            'flights' => $flights,
            'airlines' => Airline::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.flights.form', $this->formData(new Flight()));
    }

    public function store(StoreFlightRequest $request): RedirectResponse
    {
        Flight::create($request->validated() + ['available_seats' => $request->integer('total_seats')]);

        return redirect()->route('admin.flights.index')->with('success', 'Flight schedule created.');
    }

    public function edit(Flight $flight): View
    {
        return view('admin.flights.form', $this->formData($flight));
    }

    public function update(UpdateFlightRequest $request, Flight $flight): RedirectResponse
    {
        DB::transaction(function () use ($request, $flight) {
            $flight = Flight::lockForUpdate()->findOrFail($flight->id);
            $data = $request->validated();
            $bookedSeats = (int) $flight->bookings()->where('status', 'confirmed')->sum('seats_booked');

            if ($data['status'] === 'cancelled') {
                $flight->bookings()->where('status', 'confirmed')->update(['status' => 'cancelled']);
                $bookedSeats = 0;
            }

            if ($data['total_seats'] < $bookedSeats) {
                throw ValidationException::withMessages([
                    'total_seats' => "At least {$bookedSeats} seats are already booked.",
                ]);
            }

            $data['available_seats'] = $data['total_seats'] - $bookedSeats;
            $flight->update($data);
        }, 3);

        return redirect()->route('admin.flights.index')->with('success', 'Flight updated.');
    }

    public function destroy(Flight $flight): RedirectResponse
    {
        if ($flight->bookings()->exists()) {
            return back()->with('error', 'A flight with booking history cannot be deleted. Mark it cancelled instead.');
        }

        $flight->delete();

        return back()->with('success', 'Flight deleted.');
    }

    private function formData(Flight $flight): array
    {
        return [
            'flight' => $flight,
            'airlines' => Airline::orderBy('name')->get(),
            'airports' => Airport::orderBy('city')->get(),
        ];
    }
}
