<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{StoreCategoryRequest, UpdateCategoryRequest};
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('features')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(10),

            'nextOrder' => (Category::max('sort_order') ?? 0) + 1,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $validated['sort_order'] = Category::clampOrder($validated['sort_order'], Category::count() + 1);

            Category::makeRoomAtOrder($validated['sort_order']);
            Category::create($validated);
        });

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();
        $newOrder = $validated['sort_order'];

        unset($validated['sort_order']);

        DB::transaction(function () use ($category, $validated, $newOrder) {
            $category->update($validated);
            $category->moveToOrder($newOrder);
        });

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->features()->exists()) {
            return back()->with('error', "\"{$category->name}\" still has features. Move or delete them first.");
        }

        DB::transaction(function () use ($category) {
            $category->delete();
            Category::reorder();
        });

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
