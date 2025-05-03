<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Carbon Locale(Language)
    |--------------------------------------------------------------------------
    |
    | Option to whether change the language for carbon library or not.
    |
    */
    'carbon' => true,

    /*
    |--------------------------------------------------------------------------
    | Language display name
    |--------------------------------------------------------------------------
    |
    | Option to whether dispaly the language in English or Native.
    |
    */
    'native' => true,

    /*
    |--------------------------------------------------------------------------
    | All Locales (Languages)
    |--------------------------------------------------------------------------
    |
    | Uncomment the languages that your site supports - or add new ones.
    | These are sorted by the native name, which is the order you might show them in a language selector.
    |
    */
    'locales' => [
        'tr' => ['name' => 'Turkish', 'native' => 'Türkçe', 'flag_code' => 'tr'],
        'en' => ['name' => 'English', 'native' => 'English', 'flag_code' => 'us'],
        'ru' => ['name' => 'Russian', 'native' => 'Русский', 'flag_code' => 'ru'],
        'he' => ['name' => 'Hebrew', 'native' => 'עברית', 'flag_code' => 'il'],
    ],

];
