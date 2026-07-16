<?php

namespace Database\Seeders;

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            ['BG-147', 'BG', 'DAC', 'DXB', 2, 6, 45000, 180],
            ['BG-201', 'BG', 'DAC', 'LHR', 4, 12, 92000, 220],
            ['BS-105', 'BS', 'DAC', 'CGP', 1, 2, 6500, 72],
            ['BS-537', 'BS', 'DAC', 'ZYL', 3, 4, 7200, 72],
            ['EK-585', 'EK', 'DAC', 'DXB', 5, 9, 56000, 260],
            ['SQ-449', 'SQ', 'DAC', 'SIN', 7, 11, 61000, 250],
        ];

        foreach ($schedules as [$number, $airline, $from, $to, $days, $hours, $price, $seats]) {
            $flight = Flight::firstOrNew(['flight_number' => $number]);
            $flight->fill([
                'airline_id' => Airline::where('code', $airline)->value('id'),
                'departure_airport_id' => Airport::where('code', $from)->value('id'),
                'arrival_airport_id' => Airport::where('code', $to)->value('id'),
                'departure_time' => now()->addDays($days)->setTime(10, 0),
                'arrival_time' => now()->addDays($days)->setTime(10, 0)->addHours($hours),
                'price' => $price,
                'total_seats' => $seats,
                'status' => 'scheduled',
            ]);
            $bookedSeats = $flight->exists
                ? (int) $flight->bookings()->where('status', 'confirmed')->sum('seats_booked')
                : 0;
            $flight->available_seats = max(0, $seats - $bookedSeats);
            $flight->save();
        }
    }
}
