<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_number', 'airline_id', 'departure_airport_id', 'arrival_airport_id',
        'departure_time', 'arrival_time', 'price', 'total_seats', 'available_seats', 'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'arrival_time' => 'datetime',
            'price' => 'decimal:2',
            'total_seats' => 'integer',
            'available_seats' => 'integer',
        ];
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class)->withTrashed();
    }

    public function departureAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function seatAvailabilityLabel(): string
    {
        if ($this->available_seats === 0) {
            return 'Sold out';
        }

        if ($this->available_seats <= 10) {
            return 'Few seats left';
        }

        return 'Available';
    }

    public function seatAvailabilityClass(): string
    {
        if ($this->available_seats === 0) {
            return 'seat-status--sold-out';
        }

        if ($this->available_seats <= 10) {
            return 'seat-status--few';
        }

        return 'seat-status--available';
    }
}
