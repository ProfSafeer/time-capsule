<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimeCapsuleController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::prefix('capsules')->middleware(['auth:api'])->group(function () {
    Route::get('/find_all', [TimeCapsuleController::class, 'getAllCapsules']);
    Route::post('/create', [TimeCapsuleController::class, 'createTimeCapsule']);
    Route::put('/is_opened', [TimeCapsuleController::class, 'updateIsOpened']);
    Route::patch('/edit', [TimeCapsuleController::class, 'updateCapsule']);
});