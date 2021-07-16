<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getProfileDetails']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/videos', [UserController::class, 'getVideos']);
    Route::post('/user/tags', [UserController::class, 'updateOrCreateTags']);
});

/* Videos route */
Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index']);
    Route::get('/{video}', [VideoController::class, 'show'])->middleware('auth:sanctum');
    Route::post('/', [VideoController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{video}', [VideoController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{video}', [VideoController::class, 'destroy'])->middleware('auth:sanctum');
});
