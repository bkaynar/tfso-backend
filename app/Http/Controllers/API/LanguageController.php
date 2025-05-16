<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class LanguageController extends Controller
{
    public function supportedLanguages()
    {
        $languages = [
            ['code' => 'tr', 'name' => 'Türkçe', 'englishname' => 'Turkish'],
            ['code' => 'en', 'name' => 'English', 'englishname' => 'English'],
            ['code' => 'ru', 'name' => 'Русский', 'englishname' => 'Russian'],
            ['code' => 'he', 'name' => 'עברית', 'englishname' => 'Hebrew'],
        ];

        return response()->json($languages
        );
    }

    public function translations($langCode)
    {
        $validLanguages = ['tr', 'en', 'ru', 'he'];

        if (!in_array($langCode, $validLanguages)) {
            return response()->json([
                'error' => 'Invalid language code.',
            ], 400);
        }

        return response()->json([
            'language_code' => $langCode,
            'translations' => Lang::get('api', [], $langCode),
        ]);
    }
}
