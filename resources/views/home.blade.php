@extends('layouts.public')

@section('title', 'FMS - Search Flights')

@section('content')
<section class="bg-blue-600 py-14 text-white">
    <div class="mx-auto max-w-7xl px-4 text-center">
        <h1 class="text-4xl font-bold">Flight Management System</h1>
        <p class="mt-3 text-lg">Search and book flights from one simple website.</p>

        <form action="{{ route('flights.search') }}" method="GET" class="mx-auto mt-8 grid max-w-4xl gap-3 rounded bg-white p-5 text-left text-gray-700 shadow md:grid-cols-4">
            <div>
                <label class="mb-1 block text-sm font-semibold">From</label>
                <select name="from" class="w-full rounded border-gray-300">
                    <option value="">Any airport</option>
                    @foreach ($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold">To</label>
                <select name="to" class="w-full rounded border-gray-300">
                    <option value="">Any airport</option>
                    @foreach ($airports as $airport)
                        <option value="{{ $airport->id }}">{{ $airport->city }} ({{ $airport->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold">Travel date</label>
                <input type="date" name="date" min="{{ now()->toDateString() }}" class="w-full rounded border-gray-300">
            </div>
            <div class="flex items-end">
                <button class="w-full rounded bg-orange-500 px-4 py-2 font-bold text-white hover:bg-orange-600">Search Flight</button>
            </div>
        </form>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <h2 class="mb-5 text-2xl font-bold">Upcoming Flights</h2>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($featuredFlights as $flight)
            <div class="rounded border bg-white p-5 shadow-sm">
                <div class="flex justify-between">
                    <strong>{{ $flight->flight_number }}</strong>
                    <span class="rounded bg-blue-100 px-2 py-1 text-xs text-blue-700">{{ ucfirst($flight->status) }}</span>
                </div>
                <p class="mt-2 text-sm text-gray-500">{{ $flight->airline->name }}</p>
                <div class="my-4 flex items-center justify-between text-center">
                    <div><b class="text-lg">{{ $flight->departureAirport->code }}</b><br><small>{{ $flight->departureAirport->city }}</small></div>
                    <span class="text-gray-400">──── ✈</span>
                    <div><b class="text-lg">{{ $flight->arrivalAirport->code }}</b><br><small>{{ $flight->arrivalAirport->city }}</small></div>
                </div>
                <p class="text-sm">{{ $flight->departure_time->format('d M Y, h:i A') }}</p>
                <span class="seat-status {{ $flight->seatAvailabilityClass() }}">
                    {{ $flight->seatAvailabilityLabel() }} · {{ $flight->available_seats }} seats
                </span>
                <div class="mt-4 flex items-center justify-between">
                    <b class="text-blue-700">৳{{ number_format($flight->price, 2) }}</b>
                    <a href="{{ route('flights.show', $flight) }}" class="rounded bg-blue-600 px-3 py-2 text-sm text-white">View Details</a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No upcoming flights have been added yet.</p>
        @endforelse
    </div>
</section>
@endsection
