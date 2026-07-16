<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">My Bookings</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8">
        @if (session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="space-y-4">
            @forelse ($bookings as $booking)
                <div class="rounded border bg-white p-5 shadow-sm">
                    <div class="grid items-center gap-4 md:grid-cols-6">
                        <div>
                            <p class="text-xs text-gray-500">Booking reference</p>
                            <p class="font-bold text-blue-700">{{ $booking->booking_reference }}</p>
                        </div>
                        <div>
                            <p class="font-bold">{{ $booking->flight->flight_number }}</p>
                            <p class="text-sm">{{ $booking->flight->departureAirport->code }} → {{ $booking->flight->arrivalAirport->code }}</p>
                        </div>
                        <div class="text-sm">
                            {{ $booking->flight->departure_time->format('d M Y') }}<br>
                            {{ $booking->flight->departure_time->format('h:i A') }}
                        </div>
                        <div>{{ $booking->seats_booked }} seat(s)</div>
                        <div>
                            <b>৳{{ number_format($booking->total_price, 2) }}</b><br>
                            <span class="text-xs {{ $booking->status === 'confirmed' ? 'text-green-700' : 'text-red-600' }}">{{ ucfirst($booking->status) }}</span>
                        </div>
                        <div class="text-right">
                            @if ($booking->status === 'confirmed' && $booking->flight->departure_time->isFuture())
                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded border border-red-500 px-3 py-2 text-sm text-red-600">Cancel</button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">No action</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded bg-white p-8 text-center shadow">
                    <p class="text-gray-500">You have not booked any flight.</p>
                    <a href="{{ route('flights.search') }}" class="mt-3 inline-block text-blue-700">Search flights</a>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
