<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Registered Users</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-xl font-bold">User Directory</h3>
                <p class="text-sm text-gray-500">{{ $users->total() }} account(s) found</p>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search ID, name, email or phone"
                    class="rounded border-gray-300"
                >
                <button class="rounded bg-blue-700 px-4 py-2 text-white">Search</button>
                @if ($search)
                    <a href="{{ route('admin.users.index') }}" class="rounded border px-4 py-2">Clear</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto rounded bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Bookings</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-t">
                            <td class="p-3 font-semibold">#{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?: 'Not provided' }}</td>
                            <td>
                                <span class="rounded px-2 py-1 text-xs {{ $user->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-700' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                {{ $user->confirmed_bookings_count }} confirmed
                                <br>
                                <small class="text-gray-500">{{ $user->bookings_count }} total</small>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                No users matched your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    </div>
</x-app-layout>
