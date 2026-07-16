<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'airports' => Airport::orderBy('city')->get(),
            'featuredFlights' => Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
                ->whereIn('status', ['scheduled', 'delayed'])
                ->where('departure_time', '>', now())
                ->orderBy('departure_time')
                ->take(6)
                ->get(),
        ]);
    }
}
