<?php

namespace App\Providers;

use App\Models\ContentSection;
use App\Models\Message;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Short names stored in media.mediable_type instead of full class names
        Relation::enforceMorphMap([
            'property_type' => PropertyType::class,
            'content_section' => ContentSection::class,
        ]);

        Paginator::defaultView('pagination.admin');

        View::composer('partials.admin-nav', function ($view) {
            $view->with('unreadMessages', Message::unread()->count());
        });
    }
}
