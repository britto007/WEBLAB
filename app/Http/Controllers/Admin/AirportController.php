<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAirportRequest;
use App\Models\Airport;
use App\Services\WeatherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AirportController extends Controller
{
    public function index(): View
    {
        return view('admin.airports.index', [
            'airports' => Airport::withCount(['departingFlights', 'arrivingFlights'])
                ->orderBy('city')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.airports.form', ['airport' => new Airport()]);
    }

    public function store(StoreAirportRequest $request): RedirectResponse
    {
        Airport::create($request->validated());

        return redirect()->route('admin.airports.index')->with('success', 'Airport added successfully.');
    }

    public function edit(Airport $airport): View
    {
        return view('admin.airports.form', compact('airport'));
    }

    public function update(StoreAirportRequest $request, Airport $airport): RedirectResponse
    {
        $airport->update($request->validated());

        return redirect()->route('admin.airports.index')->with('success', 'Airport updated.');
    }

    public function destroy(Airport $airport): RedirectResponse
    {
        if ($airport->departingFlights()->exists() || $airport->arrivingFlights()->exists()) {
            return back()->with('error', 'This airport is used by a flight and cannot be deleted.');
        }

        $airport->delete();

        return back()->with('success', 'Airport deleted.');
    }

    public function weather(Airport $airport, WeatherService $weatherService): RedirectResponse
    {
        $weather = $weatherService->getWeatherForAirport($airport);

        return back()->with(
            $weather ? 'success' : 'error',
            $weather
                ? "{$airport->city}: {$weather['temp']}°C, {$weather['condition']}"
                : 'Weather is unavailable. Check the API key and internet connection.'
        );
    }
}
