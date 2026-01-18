<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiswaApiController;
use App\Http\Controllers\Api\KelasApiController;
use App\Http\Controllers\Api\JurusanApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ImportController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Routes untuk login, logout, dan authentication
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/me', [AuthController::class, 'me']);

/*
|--------------------------------------------------------------------------
| API Routes untuk Angular.js Frontend
|--------------------------------------------------------------------------
| Routes ini digunakan oleh Angular.js untuk CRUD data
| Semua response dalam format JSON
*/

// Import CSV Routes
Route::get('/siswa/template/download', [ImportController::class, 'downloadTemplate']);
Route::post('/siswa/import/csv', [ImportController::class, 'importCSV']);
Route::post('/siswa/import/failed-records', [ImportController::class, 'downloadFailedRecords']);

// Export Routes
Route::get('/siswa/export', [SiswaApiController::class, 'export']);
Route::get('/kelas/export', [KelasApiController::class, 'export']);
Route::get('/jurusan/export', [JurusanApiController::class, 'export']);

// API Routes untuk Siswa
Route::apiResource('siswa', SiswaApiController::class);

// API Routes untuk Kelas
Route::apiResource('kelas', KelasApiController::class);

// API Routes untuk Jurusan
Route::apiResource('jurusan', JurusanApiController::class);
