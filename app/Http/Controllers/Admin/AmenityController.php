<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAmenityRequest;
use App\Http\Requests\UpdateAmenityRequest;
use App\Models\Amenity;
use App\Support\AmenityIcons;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.amenities.index', [
            'amenities' => Amenity::orderBy('sort_order')->orderBy('title')->paginate(10),
            'nextOrder' => Amenity::nextOrder(),
            'iconOptions' => AmenityIcons::options(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmenityRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $validated['sort_order'] = Amenity::clampOrder($validated['sort_order'], Amenity::count() + 1);

            Amenity::makeRoomAtOrder($validated['sort_order']);
            Amenity::create($validated);
        });

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity created.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmenityRequest $request, Amenity $amenity): RedirectResponse
    {
        $validated = $request->validated();
        $newOrder = $validated['sort_order'];

        unset($validated['sort_order']);

        DB::transaction(function () use ($amenity, $validated, $newOrder) {
            $amenity->update($validated);
            $amenity->moveToOrder($newOrder);
        });

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amenity $amenity): RedirectResponse
    {
        DB::transaction(function () use ($amenity) {
            $amenity->delete();
            Amenity::reorder();
        });

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity deleted.');
    }
}
