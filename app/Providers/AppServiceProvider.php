<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;


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
        $locale = Session::get('admin_locale', config('app.locale'));
        $supported = ['tr', 'en', 'ru', 'he'];

        if (!in_array($locale, $supported)) {
            $locale = config('app.fallback_locale');
        }

        App::setLocale(Setting::get('panel_language', $locale));
    }
}
