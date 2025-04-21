<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-locale', function () {
    App::setLocale('ru');
    return __('resources.radios.label');
});

