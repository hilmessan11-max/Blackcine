<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Notifications\ResetPassword;
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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Rate Limiting pour les API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        // Rate Limiting pour les routes d'authentification
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by(
                $request->input('email') ?: $request->ip()
            );
        });

        // Rate Limiting pour la newsletter
        RateLimiter::for('newsletter', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('tmdb', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Invalidation cache home/footer
        $clearHome = function () {
            try {
                if (Cache::supportsTags()) {
                    Cache::tags(['home'])->flush();
                }
            } catch (\Throwable $e) {}
            Cache::forget('home_page_data');
            Cache::forget('footer_partners');
            Cache::forget('footer_stats');
        };

        foreach ([
            \App\Models\Title::class,
            \App\Models\Article::class,
            \App\Models\Video::class,
            \App\Models\Showtime::class,
            \App\Models\Selection::class,
            \App\Models\Slide::class,
            \App\Models\Festival::class,
            \App\Models\Casting::class,
            \App\Models\Project::class,
            \App\Models\Contest::class,
            \App\Models\Partner::class,
        ] as $model) {
            $model::saved($clearHome);
            $model::deleted($clearHome);
        }
    }
}
