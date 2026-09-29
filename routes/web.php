<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnonymousController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PeerStoryController;
use App\Http\Controllers\CounselorPortalController;
use App\Http\Controllers\StressModuleController;
use App\Http\Controllers\CareController;
use App\Http\Controllers\UserController;

// API Controllers
use App\Http\Controllers\Api\FeaturesController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\SessionController;

// ==========================================
// Cache Clearing & Optimization
// ==========================================
Route::get('/laravel-optimization-clear', function () {
    Artisan::call('optimize:clear');
    return 'All caches have been cleared successfully!';
});

// ==========================================
// Primary App Route (Care Group)
// Must be accessible directly via 127.0.0.1/
// ==========================================
Route::name('care.')->controller(CareController::class)->group(function () {
    // Primary Entry Point
    Route::get('/', 'chat')->name('chat');
    Route::post('/chat/send', 'send')->name('chat.send');
    
    // Sessions and Conversations
    Route::post('/care/session/restore', 'restoreSession')->name('session.restore');
    Route::post('/care/conversations/{conversation}/select', 'selectConversation')->name('conversations.select');
    
    // Counselor Chat Interface
    Route::get('/counselor-chat', 'counselor')->name('counselor');
    Route::get('/counselor-chat/sync', 'counselorSync')->name('counselor.sync');
    Route::post('/counselor-chat/request', 'requestCounselor')->name('counselor.request');
    Route::post('/counselor-chat/send', 'sendCounselor')->name('counselor.send');
    
    // Modules and Stories (Frontend)
    Route::get('/care/stress-modules', 'modules')->name('modules');
    Route::post('/care/stress-modules/{module}/download', 'download')->name('modules.download');
    Route::post('/care/stress-modules/{module}/comments', 'comment')->name('modules.comment');
    
    Route::get('/care/peer-stories', 'stories')->name('stories');
    Route::post('/care/peer-stories', 'postStory')->name('stories.post');
});

// ==========================================
// API Endpoints
// ==========================================
Route::prefix('api')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());
    
    Route::post('/session/init', [SessionController::class, 'initialize']);
    Route::post('/chat/send', [ChatController::class, 'store']);
    Route::get('/chat/history', [ChatController::class, 'history']);
    Route::get('/conversations', [ChatController::class, 'index']);
    
    Route::get('/stress-modules', [FeaturesController::class, 'getStressModules']);
    
    Route::get('/peer-stories', [FeaturesController::class, 'getPeerStories']);
    Route::post('/peer-stories/post', [FeaturesController::class, 'postPeerStory']);
    
    Route::get('/counselor/status', [FeaturesController::class, 'checkCounselorStatus']);
    Route::get('/counselor/history', [FeaturesController::class, 'getCounselorHistory']);
    Route::post('/counselor/request', [FeaturesController::class, 'requestCounselor']);
    Route::post('/conversations/create', [FeaturesController::class, 'requestCounselor'])->name('api.conversations.create');
});

// ==========================================
// Authentication & Guest Admin Routes
// ==========================================
Route::get('/admin', function () {
    return view('admin.login');
});

Route::get('login', [LoginController::class, 'show'])->name('login');
Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ==========================================
// Authenticated Routes (Admin & Counselor)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('dashboard');

    // Counselors Management
    Route::get('/counsillors', [CounselorController::class, 'index'])->name('counsillors.index');
    Route::post('/counselors/store', [CounselorController::class, 'store'])->name('counselors.store');
    Route::get('/counsillor_log', [CounselorController::class, 'assignmentLogs'])->name('counsillor_log');
    Route::patch('/counselors/{id}/toggle-status', [CounselorController::class, 'toggleStatus'])->name('counselors.toggle-status');
    Route::put('/counselors/{id}', [CounselorController::class, 'update'])->name('counselors.update');
    Route::delete('/counselors/{id}', [CounselorController::class, 'destroy'])->name('counselors.destroy');

    // Users Management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/users/{id}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');

    // Profile Management
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // System Features Management
    Route::get('/anonymous', [AnonymousController::class, 'index'])->name('anonymous.index');
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Stress Modules (Admin)
    Route::get('/stress-modules', [StressModuleController::class, 'index'])->name('stress-modules.index');
    Route::post('/stress-modules', [StressModuleController::class, 'store'])->name('stress-modules.store');
    Route::delete('/stress-modules/{id}', [StressModuleController::class, 'destroy'])->name('stress-modules.destroy');

    // Peer Stories (Admin)
    Route::get('/peer-stories', [PeerStoryController::class, 'index'])->name('peer-stories.index');
    Route::patch('/peer-stories/{id}/toggle', [PeerStoryController::class, 'toggleApproval'])->name('peer-stories.toggle');
    Route::delete('/peer-stories/{id}', [PeerStoryController::class, 'destroy'])->name('peer-stories.destroy');
    
    // Counselor Portal
    Route::prefix('counselor-portal')->group(function () {
        Route::get('/', [CounselorPortalController::class, 'index'])->name('counselor-portal.index');
        Route::get('/queue', [CounselorPortalController::class, 'queueJson'])->name('counselor.queue.json');
        Route::post('/accept/{id}', [CounselorPortalController::class, 'acceptRequest'])->name('counselor.accept');
        Route::get('/chat/{id}', [CounselorPortalController::class, 'liveChatRoom'])->name('counselor.chat');
        Route::post('/close/{id}', [CounselorPortalController::class, 'closeSession'])->name('counselor.close');
        
        Route::post('/chat/{id}/send', [CounselorPortalController::class, 'sendMessage'])->name('counselor.chat.send');
        Route::get('/chat/{id}/messages/sync', [CounselorPortalController::class, 'syncMessages'])->name('counselor.chat.sync');
    });
});