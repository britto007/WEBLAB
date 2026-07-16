<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AirportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->city().' International Airport',
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'city' => fake()->city(),
            'country' => fake()->countryCode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ];
    }
}
