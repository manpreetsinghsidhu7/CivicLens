<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes - CivicLens
|--------------------------------------------------------------------------
*/

// ========================
// PUBLIC ROUTES
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

// ========================
// AUTHENTICATION ROUTES
// ========================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========================
// AUTHENTICATED USER ROUTES
// ========================
Route::middleware('auth')->group(function () {
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/dashboard', [FeedbackController::class, 'userDashboard'])->name('user.dashboard');
});

// ========================
// ADMIN ROUTES (protected by auth + admin middleware)
// ========================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // News CRUD
    Route::get('/news', [AdminController::class, 'newsIndex'])->name('news.index');
    Route::get('/news/create', [AdminController::class, 'newsCreate'])->name('news.create');
    Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
    Route::get('/news/{id}/edit', [AdminController::class, 'newsEdit'])->name('news.edit');
    Route::put('/news/{id}', [AdminController::class, 'newsUpdate'])->name('news.update');
    Route::delete('/news/{id}', [AdminController::class, 'newsDestroy'])->name('news.destroy');

    // Fetch from NewsData.io API
    Route::post('/news/fetch-api', [NewsController::class, 'fetchFromApi'])->name('news.fetchApi');

    // Feedback Management
    Route::get('/feedback', [AdminController::class, 'feedbackIndex'])->name('feedback.index');
    Route::delete('/feedback/{id}', [AdminController::class, 'feedbackDestroy'])->name('feedback.destroy');

    // Analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
});
