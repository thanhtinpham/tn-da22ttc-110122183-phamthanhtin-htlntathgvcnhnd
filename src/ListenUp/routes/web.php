<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;

use App\Http\Controllers\PublicController;

// Trang chủ và Duyệt theo cấp độ
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/levels', [PublicController::class, 'levels'])->name('public.levels');
Route::get('/levels/{id}', [PublicController::class, 'levelDetail'])->name('public.levels.show');
Route::get('/levels/{level_id}/topics/{topic_id}', [PublicController::class, 'topicDetail'])->name('public.topics.show');
Route::get('/topics', [PublicController::class, 'topics'])->name('public.topics');
Route::get('/topics/{id}', [PublicController::class, 'topicDetailById'])->name('public.topics.detail');
Route::get('/games', [PublicController::class, 'games'])->name('public.games');
Route::get('/rankings', [PublicController::class, 'rankings'])->name('public.rankings');
Route::get('/listen', [\App\Http\Controllers\User\ListenController::class, 'index'])->name('public.listen');
Route::post('/listen/generate', [\App\Http\Controllers\User\ListenController::class, 'generate'])->name('public.listen.generate');


// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('profile.update');
    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('chude', \App\Http\Controllers\Admin\ChudeController::class);
    Route::resource('capdonghe', \App\Http\Controllers\Admin\CapdongheController::class);
    Route::resource('baitest', \App\Http\Controllers\Admin\BaitestController::class);
    
    // Test Builder Routes
    Route::get('baitest/{id}/content', [\App\Http\Controllers\Admin\BaitestController::class, 'content'])->name('baitest.content');
    Route::post('baitest/{id}/update-keyword', [\App\Http\Controllers\Admin\BaitestController::class, 'updateKeyword'])->name('baitest.updateKeyword');
    Route::post('baitest/{id}/phan', [\App\Http\Controllers\Admin\TestContentController::class, 'storePhan'])->name('phan.store');
    Route::post('phan/{id}/audio', [\App\Http\Controllers\Admin\TestContentController::class, 'storeAudio'])->name('phan.audio.store');
    Route::put('audio/{id}', [\App\Http\Controllers\Admin\TestContentController::class, 'updateAudio'])->name('audio.update');
    Route::delete('audio/{id}', [\App\Http\Controllers\Admin\TestContentController::class, 'destroyAudio'])->name('audio.destroy');
    Route::post('phan/{id}/cauhoi', [\App\Http\Controllers\Admin\TestContentController::class, 'storeCauhoi'])->name('phan.cauhoi.store');
    Route::put('cauhoi/{id}', [\App\Http\Controllers\Admin\TestContentController::class, 'updateCauhoi'])->name('cauhoi.update');
    Route::delete('cauhoi/{id}', [\App\Http\Controllers\Admin\TestContentController::class, 'destroyCauhoi'])->name('cauhoi.destroy');
    Route::delete('phan/{id}', [\App\Http\Controllers\Admin\TestContentController::class, 'destroyPhan'])->name('phan.destroy');

    Route::resource('bandophieuluu', \App\Http\Controllers\Admin\BandophieuluuController::class);
    Route::get('bandophieuluu/{id}/content', [\App\Http\Controllers\Admin\BandophieuluuController::class, 'content'])->name('bandophieuluu.content');
    Route::post('bandophieuluu/{id}/assign', [\App\Http\Controllers\Admin\BandophieuluuController::class, 'assignTest'])->name('bandophieuluu.assign');
    Route::post('bandophieuluu/{id}/unassign/{test_id}', [\App\Http\Controllers\Admin\BandophieuluuController::class, 'unassignTest'])->name('bandophieuluu.unassign');
});

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Auth Routes cho Google Login
Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialLoginController::class, 'handleGoogleCallback']);

// User routes
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/survey', [UserController::class, 'updateSurvey'])->name('profile.update-survey');
    Route::get('/lessons', [UserController::class, 'lessons'])->name('lessons');
    Route::get('/lessons/{id}', [UserController::class, 'showLesson'])->name('lessons.show');
    Route::get('/test/{id}', [UserController::class, 'showTest'])->name('test.show');
    Route::post('/test/{id}/submit', [UserController::class, 'submitTest'])->name('test.submit');
    Route::get('/results', [UserController::class, 'results'])->name('results');
    Route::get('/results/{id}', [UserController::class, 'showResult'])->name('results.show');

    // Game routes
    Route::get('/games/{id}/play', [App\Http\Controllers\User\GameController::class, 'play'])->name('games.play');
    Route::post('/games/{id}/submit', [App\Http\Controllers\User\GameController::class, 'submit'])->name('games.submit');

    // AI Assistant routes
    Route::post('/ai/chat', [\App\Http\Controllers\User\AiAssistantController::class, 'chat'])->name('ai.chat');
    Route::post('/ai/summarize', [\App\Http\Controllers\User\AiAssistantController::class, 'summarize'])->name('ai.summarize');
    Route::post('/ai/personalize', [\App\Http\Controllers\User\AiAssistantController::class, 'personalize'])->name('ai.personalize');
});