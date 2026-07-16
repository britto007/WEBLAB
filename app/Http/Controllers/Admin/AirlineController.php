<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAirlineRequest;
use App\Models\Airline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AirlineController extends Controller
{
    public function index(): View
    {
        return view('admin.airlines.index', [
            'airlines' => Airline::withCount('flights')->orderBy('name')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.airlines.form', ['airline' => new Airline()]);
    }

    public function store(StoreAirlineRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('logo');
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('airline-logos', 'public');
        }

        $airline = Airline::withTrashed()->where('code', $data['code'])->first();
        if ($airline?->trashed()) {
            $airline->restore();
            $airline->update($data);
        } else {
            Airline::create($data);
        }

        return redirect()->route('admin.airlines.index')->with('success', 'Airline added successfully.');
    }

    public function edit(Airline $airline): View
    {
        return view('admin.airlines.form', compact('airline'));
    }

    public function update(StoreAirlineRequest $request, Airline $airline): RedirectResponse
    {
        $data = $request->safe()->except('logo');
        if ($request->hasFile('logo')) {
            if ($airline->logo) {
                Storage::disk('public')->delete($airline->logo);
            }
            $data['logo'] = $request->file('logo')->store('airline-logos', 'public');
        }

        $airline->update($data);

        return redirect()->route('admin.airlines.index')->with('success', 'Airline updated.');
    }

    public function destroy(Airline $airline): RedirectResponse
    {
        $airline->delete();

        return back()->with('success', 'Airline removed from the active list.');
    }
}
