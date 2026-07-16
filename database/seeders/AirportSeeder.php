<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            ['name' => 'Hazrat Shahjalal International Airport', 'code' => 'DAC', 'city' => 'Dhaka', 'country' => 'BD', 'latitude' => 23.8433, 'longitude' => 90.3978],
            ['name' => 'Shah Amanat International Airport', 'code' => 'CGP', 'city' => 'Chattogram', 'country' => 'BD', 'latitude' => 22.2496, 'longitude' => 91.8133],
            ['name' => 'Osmani International Airport', 'code' => 'ZYL', 'city' => 'Sylhet', 'country' => 'BD', 'latitude' => 24.9632, 'longitude' => 91.8668],
            ['name' => 'Dubai International Airport', 'code' => 'DXB', 'city' => 'Dubai', 'country' => 'AE', 'latitude' => 25.2532, 'longitude' => 55.3657],
            ['name' => 'Heathrow Airport', 'code' => 'LHR', 'city' => 'London', 'country' => 'GB', 'latitude' => 51.4700, 'longitude' => -0.4543],
            ['name' => 'Singapore Changi Airport', 'code' => 'SIN', 'city' => 'Singapore', 'country' => 'SG', 'latitude' => 1.3644, 'longitude' => 103.9915],
        ];

        foreach ($airports as $airport) {
            Airport::updateOrCreate(['code' => $airport['code']], $airport);
        }
    }
}
