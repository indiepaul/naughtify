<?php

use App\Http\Controllers\NaughtifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(NaughtifyController::class)->group(function () {
    Route::post('/webhook/telegram', 'update');
    Route::post('/webhook/telegram', 'update');
    Route::post('/naughtify', 'store')->middleware(['auth:sanctum']);
    Route::post('/send', 'send')->middleware(['auth:sanctum']);
});
