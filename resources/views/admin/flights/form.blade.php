<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">{{ $flight->exists ? 'Edit Flight' : 'Add Flight Schedule' }}</h2></x-slot>
    <div class="mx-auto max-w-4xl px-4 py-8">
        <form method="POST" action="{{ $flight->exists ? route('admin.flights.update', $flight) : route('admin.flights.store') }}" class="grid gap-5 rounded bg-white p-6 shadow md:grid-cols-2">
            @csrf @if($flight->exists) @method('PUT') @endif
            <div>
                <label class="block font-semibold">Flight Number</label>
                <input name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}" placeholder="BG-147" class="mt-1 w-full rounded border-gray-300 uppercase" required>
                @error('flight_number')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Airline</label>
                <select name="airline_id" class="mt-1 w-full rounded border-gray-300" required>
                    <option value="">Select airline</option>
                    @foreach($airlines as $airline)<option value="{{ $airline->id }}" @selected(old('airline_id', $flight->airline_id) == $airline->id)>{{ $airline->name }}</option>@endforeach
                </select>
                @error('airline_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Departure Airport</label>
                <select name="departure_airport_id" class="mt-1 w-full rounded border-gray-300" required>
                    <option value="">Select airport</option>
                    @foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(old('departure_airport_id', $flight->departure_airport_id) == $airport->id)>{{ $airport->city }} ({{ $airport->code }})</option>@endforeach
                </select>
                @error('departure_airport_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Arrival Airport</label>
                <select name="arrival_airport_id" class="mt-1 w-full rounded border-gray-300" required>
                    <option value="">Select airport</option>
                    @foreach($airports as $airport)<option value="{{ $airport->id }}" @selected(old('arrival_airport_id', $flight->arrival_airport_id) == $airport->id)>{{ $airport->city }} ({{ $airport->code }})</option>@endforeach
                </select>
                @error('arrival_airport_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Departure Date & Time</label>
                <input type="datetime-local" name="departure_time" value="{{ old('departure_time', $flight->departure_time?->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('departure_time')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Arrival Date & Time</label>
                <input type="datetime-local" name="arrival_time" value="{{ old('arrival_time', $flight->arrival_time?->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('arrival_time')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Ticket Price (BDT)</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $flight->price) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('price')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Total Seats</label>
                <input type="number" min="1" name="total_seats" value="{{ old('total_seats', $flight->total_seats) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('total_seats')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Status</label>
                <select name="status" class="mt-1 w-full rounded border-gray-300">
                    @foreach(['scheduled','delayed','cancelled','completed'] as $status)<option value="{{ $status }}" @selected(old('status', $flight->status ?: 'scheduled') === $status)>{{ ucfirst($status) }}</option>@endforeach
                </select>
                @error('status')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end gap-3">
                <button class="rounded bg-blue-700 px-5 py-2 text-white">Save Flight</button>
                <a href="{{ route('admin.flights.index') }}" class="rounded border px-5 py-2">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
