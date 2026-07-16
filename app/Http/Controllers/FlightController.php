<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function search(Request $request, WeatherService $weatherService): View
    {
        $filters = $request->validate([
            'from' => ['nullable', 'integer', 'exists:airports,id', 'different:to'],
            'to' => ['nullable', 'integer', 'exists:airports,id'],
            'date' => ['nullable', 'date'],
        ]);

        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->whereIn('status', ['scheduled', 'delayed'])
            ->where('departure_time', '>', now())
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->where('departure_airport_id', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->where('arrival_airport_id', $to))
            ->when($filters['date'] ?? null, fn ($query, $date) => $query->whereDate('departure_time', $date))
            ->orderBy('departure_time')
            ->paginate(10)
            ->withQueryString();

        $weather = [];
        foreach ($flights as $flight) {
            foreach ([$flight->departureAirport, $flight->arrivalAirport] as $airport) {
                $weather[$airport->id] ??= $weatherService->getWeatherForAirport($airport);
            }
        }

        return view('flights.search', [
            'flights' => $flights,
            'airports' => Airport::orderBy('city')->get(),
            'weather' => $weather,
        ]);
    }

    public function show(Flight $flight, WeatherService $weatherService): View
    {
        $flight->load(['airline', 'departureAirport', 'arrivalAirport']);

        return view('flights.show', [
            'flight' => $flight,
            'departureWeather' => $weatherService->getWeatherForAirport($flight->departureAirport),
            'arrivalWeather' => $weatherService->getWeatherForAirport($flight->arrivalAirport),
        ]);
    }
}
