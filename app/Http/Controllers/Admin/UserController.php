<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $users = User::query()
            ->withCount([
                'bookings',
                'bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', 'confirmed'),
            ])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");

                    if (ctype_digit($search)) {
                        $query->orWhere('id', (int) $search);
                    }
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }
}
