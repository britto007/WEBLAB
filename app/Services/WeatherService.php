<?php

namespace App\Services;

use App\Models\Airport;
use App\Models\WeatherLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherService
{
    public function getWeatherForAirport(Airport $airport): ?array
    {
        if (! config('services.openweather.key')) {
            return null;
        }

        try {
            return Cache::remember("weather_airport_{$airport->id}", now()->addMinutes(30), function () use ($airport) {
                $query = [
                    'appid' => config('services.openweather.key'),
                    'units' => 'metric',
                ];

                if ($airport->latitude !== null && $airport->longitude !== null) {
                    $query += ['lat' => $airport->latitude, 'lon' => $airport->longitude];
                } else {
                    $query['q'] = "{$airport->city},{$airport->country}";
                }

                $response = Http::timeout(8)
                    ->retry(2, 200)
                    ->get(rtrim(config('services.openweather.url'), '/').'/weather', $query);

                if ($response->failed()) {
                    return null;
                }

                $json = $response->json();
                $weather = [
                    'temp' => round((float) data_get($json, 'main.temp'), 1),
                    'condition' => data_get($json, 'weather.0.main', 'Unknown'),
                    'humidity' => (int) data_get($json, 'main.humidity', 0),
                    'wind_speed' => (float) data_get($json, 'wind.speed', 0),
                    'icon' => data_get($json, 'weather.0.icon'),
                ];

                WeatherLog::create([
                    'airport_id' => $airport->id,
                    'temperature' => $weather['temp'],
                    'weather_condition' => $weather['condition'],
                    'humidity' => $weather['humidity'],
                    'wind_speed' => $weather['wind_speed'],
                    'icon' => $weather['icon'],
                    'raw_response' => $json,
                    'fetched_at' => now(),
                ]);

                return $weather;
            });
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }
}
