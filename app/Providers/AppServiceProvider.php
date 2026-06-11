<?php

namespace App\Providers;

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
        if (!app()->runningInConsole()) {
            try {
                $setting = \App\Models\Setting::first() ?? new \App\Models\Setting();
                \Illuminate\Support\Facades\View::share('global_setting', $setting);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\View::share('global_setting', new \App\Models\Setting());
            }
        }
    }
}
