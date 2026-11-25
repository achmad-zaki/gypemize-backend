<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::post('/send-email', [EmailController::class, 'send']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/campaigns', [CampaignController::class, 'index']);
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show']);
Route::get('/search', [SearchController::class, 'index']);

Route::post('/forgot-password', [PasswordResetController::class, 'forgot']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/campaigns', [CampaignController::class, 'store']);
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update']);
    Route::patch('/campaigns/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/campaigns/{campaign}/favorite', [FavoriteController::class, 'store']);
    Route::delete('/campaigns/{campaign}/favorite', [FavoriteController::class, 'destroy']);
});