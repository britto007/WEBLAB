<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Biman Bangladesh Airlines', 'code' => 'BG'],
            ['name' => 'US-Bangla Airlines', 'code' => 'BS'],
            ['name' => 'Emirates', 'code' => 'EK'],
            ['name' => 'Singapore Airlines', 'code' => 'SQ'],
        ] as $airline) {
            $model = Airline::withTrashed()->updateOrCreate(['code' => $airline['code']], $airline);
            if ($model->trashed()) {
                $model->restore();
            }
        }
    }
}
