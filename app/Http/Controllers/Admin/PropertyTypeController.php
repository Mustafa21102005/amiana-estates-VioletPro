<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use App\Http\Requests\{StorePropertyTypeRequest, UpdatePropertyTypeRequest};
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $propertyTypes = PropertyType::withCount('features')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.property-types.index', [
            'propertyTypes' => $propertyTypes,
            'canDelete' => $propertyTypes->total() > PropertyType::MINIMUM,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.property-types.create', [
            'propertyType' => new PropertyType([
                'units_count' => 1,
                'sort_order' => PropertyType::nextOrder(),
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $propertyType = null;

        DB::transaction(function () use ($validated, &$propertyType) {
            $validated['sort_order'] = PropertyType::clampOrder(
                $validated['sort_order'],
                PropertyType::count() + 1,
            );

            PropertyType::makeRoomAtOrder($validated['sort_order']);
            $propertyType = PropertyType::create($validated);
            $this->keepSingleFeatured($propertyType);
        });

        return redirect()->route('admin.property-types.edit', $propertyType)
            ->with('success', 'Property type created. You can add its features below.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PropertyType $propertyType): View
    {
        return view('admin.property-types.edit', [
            'propertyType' => $propertyType,
            'categories' => Category::orderBy('sort_order')->orderBy('name')->get(),
            'features' => $propertyType->features()->get()->groupBy('category_id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyTypeRequest $request, PropertyType $propertyType): RedirectResponse
    {
        $validated = $request->validated();
        $newOrder = $validated['sort_order'];

        unset($validated['sort_order']);

        DB::transaction(function () use ($propertyType, $validated, $newOrder) {
            $propertyType->update($validated);
            $propertyType->moveToOrder($newOrder);
            $this->keepSingleFeatured($propertyType);
        });

        return redirect()->route('admin.property-types.edit', $propertyType)->with('success', 'Property type updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PropertyType $propertyType): RedirectResponse
    {
        if (PropertyType::count() <= PropertyType::MINIMUM) {
            return back()->with('error', 'At least one property type must remain, so this one cannot be deleted. You can edit it instead.');
        }

        // The database cascade removes its features, and the HasMedia hook removes its files
        DB::transaction(function () use ($propertyType) {
            $propertyType->delete();
            PropertyType::reorder();
        });

        return back()->with('success', 'Property type deleted.');
    }

    /** At most one property type can be featured: the one just saved wins. */
    private function keepSingleFeatured(PropertyType $propertyType): void
    {
        if ($propertyType->is_featured) {
            PropertyType::whereKeyNot($propertyType->getKey())->update(['is_featured' => false]);
        }
    }
}
