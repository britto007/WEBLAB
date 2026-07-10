<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Manage Airports</h2></x-slot>
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-4 flex justify-between">
            <p class="text-gray-600">Airport city and coordinates are used for weather.</p>
            <a href="{{ route('admin.airports.create') }}" class="rounded bg-blue-700 px-4 py-2 text-white">+ Add Airport</a>
        </div>
        @if (session('success')) <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="mb-4 rounded bg-red-100 p-3 text-red-800">{{ session('error') }}</div> @endif
        <div class="overflow-x-auto rounded bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100"><tr><th class="p-3">Code</th><th>Airport</th><th>City / Country</th><th>Flights</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                @forelse ($airports as $airport)
                    <tr class="border-t">
                        <td class="p-3 font-bold">{{ $airport->code }}</td><td>{{ $airport->name }}</td><td>{{ $airport->city }}, {{ $airport->country }}</td>
                        <td>{{ $airport->departing_flights_count + $airport->arriving_flights_count }}</td>
                        <td class="whitespace-nowrap text-right">
                            <form action="{{ route('admin.airports.weather', $airport) }}" method="POST" class="inline">@csrf <button class="text-green-700">Weather</button></form>
                            <a href="{{ route('admin.airports.edit', $airport) }}" class="ml-3 text-blue-700">Edit</a>
                            <form action="{{ route('admin.airports.destroy', $airport) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Delete airport?')">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No airports added.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $airports->links() }}</div>
    </div>
</x-app-layout>
