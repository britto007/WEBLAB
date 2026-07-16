<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherLog extends Model
{
    protected $fillable = [
        'airport_id', 'temperature', 'weather_condition', 'humidity',
        'wind_speed', 'icon', 'raw_response', 'fetched_at',
    ];

    protected function casts(): array
    {
        return ['raw_response' => 'array', 'fetched_at' => 'datetime'];
    }

    public function airport(): BelongsTo
    {
        return $this->belongsTo(Airport::class);
    }
}
