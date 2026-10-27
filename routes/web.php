<?php

use App\Http\Controllers\Admin\{
    CategoryController,
    ContentSectionController,
    FeatureController,
    SettingController,
    DashboardController,
    PropertyTypeController,
    MediaController,
    AmenityController,
    MessageController
};
use App\Http\Controllers\{ContactController, ProfileController, SiteController};
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');

Route::get('residences/{property_type}', [SiteController::class, 'residence'])->name('residences.show');

Route::post('contact', [ContactController::class, 'store'])->middleware('throttle:5,10')->name('contact.store');

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/', DashboardController::class)->name('dashboard');

    Route::name('admin.')->group(function () {

        Route::resource('amenities', AmenityController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');

        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::resource('property-types', PropertyTypeController::class)->except('show');

        Route::post('property-types/{property_type}/features', [FeatureController::class, 'store'])
            ->name('property-types.features.store');

        Route::put('features/{feature}', [FeatureController::class, 'update'])->name('features.update');

        Route::delete('features/{feature}', [FeatureController::class, 'destroy'])->name('features.destroy');

        Route::post('media', [MediaController::class, 'store'])->name('media.store');

        Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');

        Route::post('media/{media}/cover', [MediaController::class, 'cover'])->name('media.cover');

        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

        Route::resource('sections', ContentSectionController::class)->only(['index', 'edit', 'update']);

        Route::resource('messages', MessageController::class)->only(['index', 'show', 'destroy']);

        Route::patch('messages/{message}/unread', [MessageController::class, 'unread'])->name('messages.unread');
    });

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__ . '/auth.php';
