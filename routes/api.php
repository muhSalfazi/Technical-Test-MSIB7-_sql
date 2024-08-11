<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\NilaiRTController;

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


Route::get('/nilaiRT', [NilaiController::class, 'nilaiRT']);
Route::get('/nilaiST', [NilaiController::class, 'nilaiST']);