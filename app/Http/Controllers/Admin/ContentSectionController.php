<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateContentSectionRequest;
use App\Models\ContentSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContentSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sections = ContentSection::withCount('media')
            ->paginate(10);

        $sections->setCollection(
            $sections->getCollection()
                ->sortBy(
                    fn(ContentSection $section) => ContentSection::positionOf($section->slug)
                )
                ->values()
        );

        return view('admin.sections.index', [
            'sections' => $sections,
            'missing' => array_values(
                array_diff(
                    array_keys(ContentSection::PLACEMENT),
                    ContentSection::pluck('slug')->all()
                )
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContentSection $section): View
    {
        return view('admin.sections.edit', ['section' => $section]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContentSectionRequest $request, ContentSection $section): RedirectResponse
    {
        $section->update($request->validated());

        return redirect()->route('admin.sections.edit', $section)->with('success', 'Section saved.');
    }
}
