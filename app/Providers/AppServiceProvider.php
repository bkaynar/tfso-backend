<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $supportedLocales = ['tr', 'en', 'ru', 'he'];
        $locale = 'en';

        if (Auth::check()) {
            $locale = Auth::user()->locale;
        }

        // Eğer kullanıcı giriş yapmamışsa, session'ı temizle
        if (!Auth::check()) {
            Session::forget('filament_language_switch_locale');
        }

        // Session'da geçici dil tercihi varsa al
        $locale = Session::get('filament_language_switch_locale', $locale);

        // Fallback
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.fallback_locale', 'en');
        }

        App::setLocale($locale);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) use ($supportedLocales) {
            $switch
                ->flags([
                    'tr' => asset('assets/tr.svg'),
                    'en' => asset('assets/en.svg'),
                    'ru' => asset('assets/ru.svg'),
                    'he' => asset('assets/he.svg'),
                ])
                ->locales($supportedLocales)
                ->nativeLabel();
        });
    }
}
