<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FeaturesController;
use App\Http\Controllers\ClientChatRequestController;

// Authentication
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Session Management
Route::post('/session/init', [SessionController::class, 'initialize']);

// Chat Operations
Route::controller(ChatController::class)->group(function () {
    Route::post('/chat/send', 'store');
    Route::get('/chat/history', 'history');
    Route::get('/conversations', 'index');
});

// Feature Matrix
Route::controller(FeaturesController::class)->group(function () {
    Route::get('/stress-modules', 'getStressModules');
    Route::get('/peer-stories', 'getPeerStories');
    Route::post('/peer-stories/post', 'postPeerStory');
    Route::get('/counselor/status', 'checkCounselorStatus');
    Route::get('/counselor/history', 'getCounselorHistory');
    Route::post('/counselor/request', 'requestCounselor');
});

// Client Chat Request Pipeline
// Note: If this file is already in routes/api.php, the path should be '/conversations/create'
Route::post('/conversations/create', [ClientChatRequestController::class, 'store'])->name('api.conversations.create');
