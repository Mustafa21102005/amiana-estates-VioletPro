<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;
use App\Models\Feature;
use App\Models\PropertyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeatureController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeatureRequest $request, PropertyType $propertyType): RedirectResponse
    {
        $categoryId = (int) $request->validated('category_id');
        $titles = $request->titles();

        // New features go to the end of this category, in the order they were typed
        $start = Feature::nextOrder([
            'property_type_id' => $propertyType->id,
            'category_id' => $categoryId,
        ]);

        $propertyType->features()->createMany(
            collect($titles)->map(fn($title, $index) => [
                'title' => $title,
                'category_id' => $categoryId,
                'sort_order' => $start + $index,
            ])->all()
        );

        $count = count($titles);

        return $this->backToProperty($propertyType)
            ->with('success', $count . ' ' . Str::plural('feature', $count) . ' added.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeatureRequest $request, Feature $feature): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($feature, $validated) {
            $oldGroup = [
                'property_type_id' => $feature->property_type_id,
                'category_id' => $feature->category_id,
            ];

            $feature->title = $validated['title'];

            if ((int) $validated['category_id'] !== (int) $feature->category_id) {
                // Moving to another category: park it at the end of the new one,
                // close the gap it left behind, then honour the requested position.
                $feature->category_id = $validated['category_id'];
                $feature->sort_order = Feature::nextOrder([
                    'property_type_id' => $feature->property_type_id,
                    'category_id' => $feature->category_id,
                ]);
                $feature->save();

                Feature::reorder($oldGroup);
            } else {
                $feature->save();
            }

            $feature->moveToOrder($validated['sort_order']);
        });

        return $this->backToProperty($feature->propertyType)->with('success', 'Feature updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature): RedirectResponse
    {
        $propertyType = $feature->propertyType;
        $group = [
            'property_type_id' => $feature->property_type_id,
            'category_id' => $feature->category_id,
        ];

        DB::transaction(function () use ($feature, $group) {
            $feature->delete();
            Feature::reorder($group);
        });

        return $this->backToProperty($propertyType)->with('success', 'Feature deleted.');
    }

    private function backToProperty(PropertyType $propertyType): RedirectResponse
    {
        return redirect(route('admin.property-types.edit', $propertyType) . '#features');
    }
}
