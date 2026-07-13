<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">All Bookings</h2></x-slot>
    <div class="mx-auto max-w-7xl px-4 py-8">
        @if(session('success'))<div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>@endif
        <form method="GET" class="mb-4 grid gap-3 rounded bg-white p-4 shadow sm:grid-cols-3">
            <select name="flight_id" class="rounded border-gray-300"><option value="">All flights</option>@foreach($flights as $flight)<option value="{{ $flight->id }}" @selected(request('flight_id') == $flight->id)>{{ $flight->flight_number }}</option>@endforeach</select>
            <select name="status" class="rounded border-gray-300"><option value="">All status</option><option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option><option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option></select>
            <button class="rounded bg-gray-700 px-4 py-2 text-white">Filter Bookings</button>
        </form>
        <div class="overflow-x-auto rounded bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100"><tr><th class="p-3">Reference</th><th>Passenger</th><th>Flight</th><th>Route</th><th>Seats</th><th>Total</th><th>Status</th><th class="text-right">Action</th></tr></thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr class="border-t">
                        <td class="p-3 font-semibold">{{ $booking->booking_reference }}</td>
                        <td>{{ $booking->user->name }}<br><small>{{ $booking->user->email }}</small></td>
                        <td>{{ $booking->flight->flight_number }}</td><td>{{ $booking->flight->departureAirport->code }} → {{ $booking->flight->arrivalAirport->code }}</td>
                        <td>{{ $booking->seats_booked }}</td><td>৳{{ number_format($booking->total_price, 2) }}</td><td>{{ ucfirst($booking->status) }}</td>
                        <td class="text-right">
                            @if($booking->status === 'confirmed')
                                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Cancel this booking?')">@csrf @method('PATCH')<button class="text-red-600">Cancel</button></form>
                            @else <span class="text-gray-400">None</span> @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="p-6 text-center text-gray-500">No bookings found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
