@extends('layouts.public')

@section('title', $flight->flight_number.' Details')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8">
    <a href="{{ url()->previous() }}" class="text-sm text-blue-700">← Back to flights</a>

    <div class="mt-4 rounded border bg-white p-6 shadow">
        <div class="flex flex-wrap items-start justify-between gap-3 border-b pb-4">
            <div>
                <h1 class="text-3xl font-bold">{{ $flight->flight_number }}</h1>
                <p class="text-gray-500">{{ $flight->airline->name }}</p>
            </div>
            <span class="rounded bg-blue-100 px-3 py-1 text-blue-700">{{ ucfirst($flight->status) }}</span>
        </div>

        <div class="grid gap-6 py-8 text-center md:grid-cols-3">
            <div>
                <p class="text-4xl font-bold">{{ $flight->departureAirport->code }}</p>
                <p>{{ $flight->departureAirport->name }}</p>
                <p class="mt-2 font-semibold">{{ $flight->departure_time->format('d M Y, h:i A') }}</p>
            </div>
            <div class="self-center text-gray-400">
                <p>{{ $flight->departure_time->diffForHumans($flight->arrival_time, true) }}</p>
                <p class="text-2xl">──────── ✈</p>
            </div>
            <div>
                <p class="text-4xl font-bold">{{ $flight->arrivalAirport->code }}</p>
                <p>{{ $flight->arrivalAirport->name }}</p>
                <p class="mt-2 font-semibold">{{ $flight->arrival_time->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            @foreach ([[$flight->departureAirport, $departureWeather], [$flight->arrivalAirport, $arrivalWeather]] as [$airport, $currentWeather])
                <div class="rounded border bg-blue-50 p-4">
                    <h3 class="font-bold">Current Weather in {{ $airport->city }}</h3>
                    @if ($currentWeather)
                        <p class="mt-2 text-2xl">{{ $currentWeather['temp'] }}°C</p>
                        <p class="text-sm">{{ $currentWeather['condition'] }} · Humidity {{ $currentWeather['humidity'] }}% · Wind {{ $currentWeather['wind_speed'] }} m/s</p>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Weather unavailable right now.</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-t pt-5">
            <div>
                <p class="text-sm text-gray-500">Price per passenger</p>
                <p class="text-3xl font-bold text-blue-700">৳{{ number_format($flight->price, 2) }}</p>
                <p class="text-sm">{{ $flight->available_seats }} of {{ $flight->total_seats }} seats available</p>
                <span class="seat-status {{ $flight->seatAvailabilityClass() }}">
                    {{ $flight->seatAvailabilityLabel() }}
                </span>
            </div>

            @if ($flight->available_seats > 0 && $flight->status === 'scheduled' && $flight->departure_time->isFuture())
                @auth
                    @if (! auth()->user()->isAdmin())
                        <form action="{{ route('bookings.store') }}" method="POST" class="flex items-end gap-2">
                            @csrf
                            <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                            <div>
                                <label class="block text-sm font-semibold">Seats</label>
                                <input type="number" name="seats_booked" value="{{ old('seats_booked', 1) }}" min="1" max="{{ min(10, $flight->available_seats) }}" class="w-24 rounded border-gray-300">
                            </div>
                            <button class="rounded bg-orange-500 px-5 py-2 font-bold text-white">Confirm Booking</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="rounded bg-orange-500 px-5 py-3 font-bold text-white">Login to Book</a>
                @endauth
            @else
                <span class="rounded bg-gray-200 px-5 py-3 text-gray-600">Booking unavailable</span>
            @endif
        </div>

        @if ($errors->any())
            <div class="mt-4 rounded bg-red-100 p-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
    </div>
</div>
@endsection
