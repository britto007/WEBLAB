<x-app-layout>
    <style>
        .flight-row {
            display: grid;
            grid-template-columns: 28% 24% 35% 13%;
            align-items: center;
        }

        @media (max-width: 640px) {
            .flight-row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Passenger Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded bg-blue-700 p-6 text-white shadow">
                    <p class="text-sm">Welcome back</p>
                    <p class="text-2xl font-bold">{{ auth()->user()->name }}</p>
                </div>
                <div class="rounded bg-white p-6 shadow">
                    <p class="text-sm text-gray-500">Active bookings</p>
                    <p class="text-3xl font-bold">{{ $bookingCount }}</p>
                </div>
                <div class="flex items-center rounded bg-white p-6 shadow">
                    <a href="{{ route('flights.search') }}" class="rounded bg-orange-500 px-5 py-3 font-bold text-white">Search New Flight</a>
                </div>
            </div>

            <div class="mt-6 rounded bg-white p-6 shadow">
                <div class="mb-4 flex justify-between">
                    <h3 class="text-xl font-bold">Upcoming Available Flights</h3>
                    <a href="{{ route('bookings.index') }}" class="text-blue-700">My Bookings</a>
                </div>
                <div class="divide-y">
                    @forelse ($upcomingFlights as $flight)
                        <div class="flight-row py-4">
                            <div>
                                <b>{{ $flight->flight_number }}</b><br>
                                <small>{{ $flight->airline->name }}</small>
                                <br>
                                <span class="seat-status {{ $flight->seatAvailabilityClass() }}">
                                    {{ $flight->seatAvailabilityLabel() }}
                                </span>
                            </div>
                            <div>
                                {{ $flight->departureAirport->code }} → {{ $flight->arrivalAirport->code }}
                            </div>
                            <div>
                                {{ $flight->departure_time->format('d M Y, h:i A') }}
                            </div>
                            <div class="text-right">
                                <a href="{{ route('flights.show', $flight) }}" class="inline-block rounded bg-blue-600 px-3 py-2 text-sm text-white">Details</a>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-gray-500">No upcoming flights.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
