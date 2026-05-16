<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes - CivicLens REST API
|--------------------------------------------------------------------------
*/

Route::get('/news', [ApiController::class, 'newsIndex']);
Route::get('/news/{id}', [ApiController::class, 'newsShow']);
Route::post('/feedback', [ApiController::class, 'feedbackStore']);
Route::get('/feedback', [ApiController::class, 'feedbackIndex']);
