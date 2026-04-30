<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ChatController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/session/init', [SessionController::class, 'initialize']);
Route::post('/chat/send', [ChatController::class, 'store']);
Route::get('/chat/history', [App\Http\Controllers\Api\ChatController::class, 'history']);
Route::post('/chat/send', [ChatController::class, 'store']);
Route::get('/chat/history', [ChatController::class, 'history']);
Route::get('/conversations', [ChatController::class, 'index']);
