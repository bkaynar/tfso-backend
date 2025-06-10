<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\Api\AccessLogController;
use App\Http\Controllers\API\DJController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//Desteklenen Diller
Route::get('/available-languages', [LanguageController::class, 'supportedLanguages']);
Route::get('/translations/all/{langCode}', [LanguageController::class, 'translations']);
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/access-logs', [AccessLogController::class, 'index']);
    Route::get('/djs', [DJController::class, 'index']);
    Route::get('/djs/{id}', [DJController::class, 'show']);

});
