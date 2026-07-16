<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'city', 'country', 'latitude', 'longitude'];

    protected function casts(): array
    {
        return ['latitude' => 'decimal:7', 'longitude' => 'decimal:7'];
    }

    public function departingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }

    public function weatherLogs(): HasMany
    {
        return $this->hasMany(WeatherLog::class);
    }
}
