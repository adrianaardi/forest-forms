<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Blade::directive('titlecase', fn($expression) => "<?php echo e(\Illuminate\Support\Str::title($expression)); ?>");

        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) 
            {
            /** @var \PDO $pdo */
            $pdo = DB::connection()->getPdo();
        
            $pdo->sqliteCreateFunction('REGEXP_REPLACE', function ($pattern, $replacement, $subject) 
            {
                if ($subject === null) return null;
                return preg_replace("/{$pattern}/", $replacement, $subject);
            }, 3);
        }
    }
}

