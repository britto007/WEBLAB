<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightFactory extends Factory
{
    public function definition(): array
    {
        $departure = now()->addDays(fake()->numberBetween(1, 30));

        return [
            'flight_number' => strtoupper(fake()->bothify('??-###')),
            'airline_id' => Airline::factory(),
            'departure_airport_id' => Airport::factory(),
            'arrival_airport_id' => Airport::factory(),
            'departure_time' => $departure,
            'arrival_time' => $departure->copy()->addHours(fake()->numberBetween(1, 12)),
            'price' => fake()->randomFloat(2, 3000, 100000),
            'total_seats' => 150,
            'available_seats' => 150,
            'status' => 'scheduled',
        ];
    }
}
