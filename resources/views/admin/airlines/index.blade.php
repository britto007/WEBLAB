<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Manage Airlines</h2></x-slot>
    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-4 flex justify-between">
            <p class="text-gray-600">Airlines used in our flight schedules.</p>
            <a href="{{ route('admin.airlines.create') }}" class="rounded bg-blue-700 px-4 py-2 text-white">+ Add Airline</a>
        </div>
        @if (session('success')) <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div> @endif
        <div class="overflow-x-auto rounded bg-white shadow">
            <table class="w-full text-left">
                <thead class="bg-gray-100"><tr><th class="p-3">Logo</th><th>Name</th><th>Code</th><th>Flights</th><th class="text-right">Actions</th></tr></thead>
                <tbody>
                @forelse ($airlines as $airline)
                    <tr class="border-t">
                        <td class="p-3">@if($airline->logo)<img src="{{ asset('storage/'.$airline->logo) }}" class="h-10 w-10 object-contain">@else <span class="text-gray-400">No logo</span>@endif</td>
                        <td>{{ $airline->name }}</td><td>{{ $airline->code }}</td><td>{{ $airline->flights_count }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.airlines.edit', $airline) }}" class="text-blue-700">Edit</a>
                            <form action="{{ route('admin.airlines.destroy', $airline) }}" method="POST" class="ml-3 inline" onsubmit="return confirm('Remove this airline?')">
                                @csrf @method('DELETE') <button class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">No airlines added.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $airlines->links() }}</div>
    </div>
</x-app-layout>
