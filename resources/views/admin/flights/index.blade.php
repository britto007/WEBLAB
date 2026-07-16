<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Manage Flights</h2></x-slot>
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-4 flex justify-between">
            <p class="text-gray-600">Create and update flight schedules.</p>
            <a href="{{ route('admin.flights.create') }}" class="rounded bg-blue-700 px-4 py-2 text-white">+ Add Flight</a>
        </div>
        @if (session('success'))<div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="mb-4 rounded bg-red-100 p-3 text-red-800">{{ session('error') }}</div>@endif
        <form method="GET" class="mb-4 grid gap-3 rounded bg-white p-4 shadow sm:grid-cols-4">
            <select name="airline" class="rounded border-gray-300"><option value="">All airlines</option>@foreach($airlines as $airline)<option value="{{ $airline->id }}" @selected(request('airline') == $airline->id)>{{ $airline->name }}</option>@endforeach</select>
            <select name="status" class="rounded border-gray-300"><option value="">All status</option>@foreach(['scheduled','delayed','cancelled','completed'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
            <input type="date" name="date" value="{{ request('date') }}" class="rounded border-gray-300">
            <button class="rounded bg-gray-700 px-4 py-2 text-white">Filter</button>
        </form>
        <div class="overflow-x-auto rounded bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100"><tr><th class="p-3">Flight</th><th>Route</th><th>Departure</th><th>Seats</th><th>Price</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                @forelse($flights as $flight)
                    <tr class="border-t">
                        <td class="p-3"><b>{{ $flight->flight_number }}</b><br><small>{{ $flight->airline->code }}</small></td>
                        <td>{{ $flight->departureAirport->code }} → {{ $flight->arrivalAirport->code }}</td>
                        <td>{{ $flight->departure_time->format('d M Y') }}<br>{{ $flight->departure_time->format('h:i A') }}</td>
                        <td>{{ $flight->available_seats }}/{{ $flight->total_seats }}<br><small>{{ $flight->bookings_count }} bookings</small></td>
                        <td>৳{{ number_format($flight->price, 2) }}</td><td>{{ ucfirst($flight->status) }}</td>
                        <td class="whitespace-nowrap text-right">
                            <a href="{{ route('admin.flights.edit', $flight) }}" class="text-blue-700">Edit</a>
                            <form action="{{ route('admin.flights.destroy', $flight) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete this flight?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">No flights found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $flights->links() }}</div>
    </div>
</x-app-layout>
