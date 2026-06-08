<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\News; 
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
            if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer(['admin.pergerakan.*', 'main-dashboard', 'subadmin-dashboard'], function ($view) {
            $rollingNews = News::where('is_active', true)->latest()->pluck('headline')->toArray();
            
            // Combine all headlines into a single string separated by bullets
            $newsTickerText = implode(' • ', $rollingNews);
            
            $view->with('newsTickerText', $newsTickerText);
        });
    }
}
