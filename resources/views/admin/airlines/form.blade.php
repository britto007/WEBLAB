
<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">{{ $airline->exists ? 'Edit Airline' : 'Add Airline' }}</h2></x-slot>
    <div class="mx-auto max-w-2xl px-4 py-8">
        <form method="POST" enctype="multipart/form-data" action="{{ $airline->exists ? route('admin.airlines.update', $airline) : route('admin.airlines.store') }}" class="space-y-5 rounded bg-white p-6 shadow">
            @csrf
            @if($airline->exists) @method('PUT') @endif
            <div>
                <label class="block font-semibold">Airline Name</label>
                <input name="name" value="{{ old('name', $airline->name) }}" class="mt-1 w-full rounded border-gray-300" required>
                @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block font-semibold">IATA Code</label>
                <input name="code" value="{{ old('code', $airline->code) }}" maxlength="3" class="mt-1 w-full rounded border-gray-300 uppercase" required>
                @error('code') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block font-semibold">Logo (optional)</label>
                <input type="file" name="logo" accept="image/*" class="mt-1 w-full rounded border p-2">
                @error('logo') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3">
                <button class="rounded bg-blue-700 px-5 py-2 text-white">Save Airline</button>
                <a href="{{ route('admin.airlines.index') }}" class="rounded border px-5 py-2">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
