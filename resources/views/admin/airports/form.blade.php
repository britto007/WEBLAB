<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">{{ $airport->exists ? 'Edit Airport' : 'Add Airport' }}</h2></x-slot>
    <div class="mx-auto max-w-3xl px-4 py-8">
        <form method="POST" action="{{ $airport->exists ? route('admin.airports.update', $airport) : route('admin.airports.store') }}" class="grid gap-5 rounded bg-white p-6 shadow md:grid-cols-2">
            @csrf @if($airport->exists) @method('PUT') @endif
            <div class="md:col-span-2">
                <label class="block font-semibold">Airport Name</label>
                <input name="name" value="{{ old('name', $airport->name) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">IATA Code</label>
                <input name="code" value="{{ old('code', $airport->code) }}" maxlength="3" class="mt-1 w-full rounded border-gray-300 uppercase" required>
                @error('code')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">City</label>
                <input name="city" value="{{ old('city', $airport->city) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('city')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Country Code / Name</label>
                <input name="country" value="{{ old('country', $airport->country) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('country')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div></div>
            <div>
                <label class="block font-semibold">Latitude (optional)</label>
                <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $airport->latitude) }}" class="mt-1 w-full rounded border-gray-300">
                @error('latitude')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-semibold">Longitude (optional)</label>
                <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $airport->longitude) }}" class="mt-1 w-full rounded border-gray-300">
                @error('longitude')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 md:col-span-2">
                <button class="rounded bg-blue-700 px-5 py-2 text-white">Save Airport</button>
                <a href="{{ route('admin.airports.index') }}" class="rounded border px-5 py-2">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
