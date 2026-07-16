<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard', [
            'upcomingFlights' => Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
                ->whereIn('status', ['scheduled', 'delayed'])
                ->where('departure_time', '>', now())
                ->orderBy('departure_time')
                ->take(5)
                ->get(),
            'bookingCount' => Booking::where('user_id', $request->user()->id)
                ->where('status', 'confirmed')
                ->count(),
        ]);
    }
}
