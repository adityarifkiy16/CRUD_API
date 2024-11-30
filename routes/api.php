<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Mahasiswa
    Route::get('/mahasiswa/create', [\App\Http\Controllers\Api\MahasiswaController::class, 'store']);
    Route::get('/mahasiswa/read', [\App\Http\Controllers\Api\MahasiswaController::class, 'index']);
    Route::put('/mahasiswa/update', [\App\Http\Controllers\Api\MahasiswaController::class, 'update']);
    Route::delete('/mahasiswa/delete', [\App\Http\Controllers\Api\MahasiswaController::class, 'destroy']);
    // Dosen
    Route::get('/dosen/create', [\App\Http\Controllers\Api\DosenController::class, 'store']);
    Route::get('/dosen/read', [\App\Http\Controllers\Api\DosenController::class, 'index']);
    Route::put('/dosen/update', [\App\Http\Controllers\Api\DosenController::class, 'update']);
    Route::delete('/dosen/delete', [\App\Http\Controllers\Api\DosenController::class, 'destroy']);
    //Makul
    Route::get('/makul/create', [\App\Http\Controllers\Api\MakulController::class, 'store']);
    Route::get('/makul/read', [\App\Http\Controllers\Api\MakulController::class, 'index']);
    Route::put('/makul/update', [\App\Http\Controllers\Api\MakulController::class, 'update']);
    Route::delete('/makul/delete', [\App\Http\Controllers\Api\MakulController::class, 'destroy']);


    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
