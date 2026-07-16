<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalFlights' => Flight::count(),
            'totalBookings' => Booking::count(),
            'totalRevenue' => Booking::where('status', 'confirmed')->sum('total_price'),
            'totalUsers' => User::where('role', 'user')->count(),
            'recentBookings' => Booking::with(['user', 'flight'])->latest()->take(5)->get(),
            'confirmedBookings' => Booking::where('status', 'confirmed')->count(),
            'popularRoutes' => Booking::query()
                ->join('flights', 'bookings.flight_id', '=', 'flights.id')
                ->join('airports as departure_airports', 'flights.departure_airport_id', '=', 'departure_airports.id')
                ->join('airports as arrival_airports', 'flights.arrival_airport_id', '=', 'arrival_airports.id')
                ->where('bookings.status', 'confirmed')
                ->select([
                    'flights.departure_airport_id',
                    'flights.arrival_airport_id',
                    'departure_airports.code as departure_code',
                    'departure_airports.city as departure_city',
                    'arrival_airports.code as arrival_code',
                    'arrival_airports.city as arrival_city',
                    DB::raw('COUNT(bookings.id) as booking_count'),
                ])
                ->groupBy(
                    'flights.departure_airport_id',
                    'flights.arrival_airport_id',
                    'departure_airports.code',
                    'departure_airports.city',
                    'arrival_airports.code',
                    'arrival_airports.city'
                )
                ->orderByDesc('booking_count')
                ->limit(5)
                ->get(),
        ]);
    }
}
