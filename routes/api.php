<?php

use App\Http\Controllers\API\EpresenceController;
use App\Http\Controllers\API\UsersController;
use App\Models\Epresence;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UsersController::class, 'logout']);

    Route::post('absen', [EpresenceController::class, 'store']);
    Route::post('approve/{id}', [EpresenceController::class, 'approve']);
});

Route::post('v1/login', [UsersController::class, 'login']);
Route::post('v1/register', [UsersController::class, 'register']);

Route::get('getabsen', [EpresenceController::class, 'getDataAbsen']);
