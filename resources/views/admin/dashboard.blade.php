<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Admin Dashboard</h2></x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded bg-blue-600 p-5 text-white shadow"><p>Total Flights</p><b class="text-3xl">{{ $totalFlights }}</b></div>
            <div class="rounded bg-green-600 p-5 text-white shadow"><p>Total Bookings</p><b class="text-3xl">{{ $totalBookings }}</b></div>
            <div class="rounded bg-orange-500 p-5 text-white shadow"><p>Confirmed Revenue</p><b class="text-3xl">৳{{ number_format($totalRevenue, 2) }}</b></div>
            <div class="rounded bg-purple-600 p-5 text-white shadow"><p>Registered Users</p><b class="text-3xl">{{ $totalUsers }}</b></div>
        </div>

        <div class="mt-6 rounded bg-white p-6 shadow">
            <div class="mb-4">
                <h3 class="text-lg font-bold">Favourite Routes Report</h3>
                <p class="text-sm text-gray-500">Routes ranked by the number of confirmed bookings.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Rank</th>
                            <th>Route</th>
                            <th>Cities</th>
                            <th>Confirmed Bookings</th>
                            <th>Share</th>
                            <th class="text-right">Search Flights</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($popularRoutes as $route)
                            <tr class="border-b">
                                <td class="p-3 font-bold">#{{ $loop->iteration }}</td>
                                <td class="font-bold text-blue-700">{{ $route->departure_code }} → {{ $route->arrival_code }}</td>
                                <td>{{ $route->departure_city }} to {{ $route->arrival_city }}</td>
                                <td>{{ $route->booking_count }}</td>
                                <td>
                                    {{ $confirmedBookings > 0 ? number_format(($route->booking_count / $confirmedBookings) * 100, 1) : 0 }}%
                                </td>
                                <td class="text-right">
                                    <a
                                        href="{{ route('flights.search', ['from' => $route->departure_airport_id, 'to' => $route->arrival_airport_id]) }}"
                                        class="inline-block rounded bg-blue-600 px-3 py-2 text-xs text-white"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">
                                    Route statistics will appear after passengers make confirmed bookings.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 rounded bg-white p-6 shadow">
            <h3 class="mb-4 text-lg font-bold">Recent Bookings</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100"><tr><th class="p-3">Reference</th><th>User</th><th>Flight</th><th>Amount</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse ($recentBookings as $booking)
                        <tr class="border-b"><td class="p-3">{{ $booking->booking_reference }}</td><td>{{ $booking->user->name }}</td><td>{{ $booking->flight->flight_number }}</td><td>৳{{ number_format($booking->total_price, 2) }}</td><td>{{ ucfirst($booking->status) }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="p-4 text-center text-gray-500">No bookings yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
