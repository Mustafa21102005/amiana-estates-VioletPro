<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => Setting::map()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        Setting::write($request->validated());

        return redirect()->route('admin.settings.edit')->with('success', 'Settings saved.');
    }
}
