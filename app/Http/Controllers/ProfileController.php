<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        DB::transaction(function () use ($user) {
            $flightIds = Booking::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->orderBy('flight_id')
                ->pluck('flight_id')
                ->unique();

            foreach ($flightIds as $flightId) {
                $flight = Flight::lockForUpdate()->find($flightId);
                $seats = Booking::where('user_id', $user->id)
                    ->where('flight_id', $flightId)
                    ->where('status', 'confirmed')
                    ->lockForUpdate()
                    ->sum('seats_booked');

                if ($flight && $seats > 0) {
                    $flight->increment('available_seats', $seats);
                }
            }

            $user->delete();
        }, 3);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
