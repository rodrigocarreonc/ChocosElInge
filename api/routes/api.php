<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SaborController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('productos', [ProductoController::class,'all']);
Route::post('productos', [ProductoController::class,'create']);
Route::put('productos/{id}', [ProductoController::class,'update']);
Route::delete('productos/{id}', [ProductoController::class,'delete']);

Route::get('sabores', [SaborController::class,'get']);
Route::post('sabores', [SaborController::class,'create']);
Route::put('sabores/{id}', [SaborController::class,'update']);
Route::delete('sabores/{id}', [SaborController::class,'delete']);