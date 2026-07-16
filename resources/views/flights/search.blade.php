@extends('layouts.public')

@section('title', 'Search Flights')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8">
    <h1 class="mb-5 text-3xl font-bold">Search Available Flights</h1>

    <form method="GET" class="grid gap-3 rounded border bg-white p-5 shadow-sm md:grid-cols-4">
        <select name="from" class="rounded border-gray-300">
            <option value="">From any airport</option>
            @foreach ($airports as $airport)
                <option value="{{ $airport->id }}" @selected(request('from') == $airport->id)>{{ $airport->city }} ({{ $airport->code }})</option>
            @endforeach
        </select>
        <select name="to" class="rounded border-gray-300">
            <option value="">To any airport</option>
            @foreach ($airports as $airport)
                <option value="{{ $airport->id }}" @selected(request('to') == $airport->id)>{{ $airport->city }} ({{ $airport->code }})</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="rounded border-gray-300">
        <button class="rounded bg-blue-700 px-5 py-2 font-semibold text-white">Search</button>
    </form>

    @if ($errors->any())
        <div class="mt-3 rounded bg-red-100 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <p class="my-5 text-gray-600">{{ $flights->total() }} flight(s) found</p>

    <div class="space-y-4">
        @forelse ($flights as $flight)
            <div class="rounded border bg-white p-5 shadow-sm">
                <div class="grid items-center gap-5 md:grid-cols-5">
                    <div>
                        <p class="font-bold text-blue-700">{{ $flight->flight_number }}</p>
                        <p class="text-sm">{{ $flight->airline->name }}</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold">{{ $flight->departure_time->format('h:i A') }}</p>
                        <p>{{ $flight->departureAirport->city }} ({{ $flight->departureAirport->code }})</p>
                        @if ($weather[$flight->departureAirport->id] ?? null)
                            <p class="mt-1 text-xs text-gray-500">🌤 {{ $weather[$flight->departureAirport->id]['temp'] }}°C, {{ $weather[$flight->departureAirport->id]['condition'] }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-400">Weather unavailable</p>
                        @endif
                    </div>
                    <div class="text-center text-sm text-gray-500">
                        {{ $flight->departure_time->diffForHumans($flight->arrival_time, true) }}<br>──── ✈
                    </div>
                    <div>
                        <p class="text-xl font-bold">{{ $flight->arrival_time->format('h:i A') }}</p>
                        <p>{{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->code }})</p>
                        @if ($weather[$flight->arrivalAirport->id] ?? null)
                            <p class="mt-1 text-xs text-gray-500">🌤 {{ $weather[$flight->arrivalAirport->id]['temp'] }}°C, {{ $weather[$flight->arrivalAirport->id]['condition'] }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-400">Weather unavailable</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-blue-700">৳{{ number_format($flight->price, 2) }}</p>
                        <p class="mb-2 text-xs">{{ $flight->available_seats }} seats left</p>
                        <span class="seat-status {{ $flight->seatAvailabilityClass() }}">
                            {{ $flight->seatAvailabilityLabel() }}
                        </span>
                        <br>
                        <a href="{{ route('flights.show', $flight) }}" class="mt-2 inline-block rounded bg-orange-500 px-4 py-2 text-sm font-semibold text-white">View & Book</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded border bg-white p-8 text-center text-gray-500">No flights matched your search.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $flights->links() }}</div>
</div>
@endsection
