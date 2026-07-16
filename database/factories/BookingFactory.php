<?php

namespace Database\Factories;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function ($booking) {
            if ($booking->status === 'confirmed') {
                $booking->flight()->decrement('available_seats', $booking->seats_booked);
            }
        });
    }

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'flight_id' => Flight::factory(),
            'booking_reference' => 'FMS'.strtoupper(Str::random(7)),
            'seats_booked' => 1,
            'total_price' => 5000,
            'status' => 'confirmed',
        ];
    }
}
