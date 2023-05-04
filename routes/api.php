<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Earring\CategoriasController;
use App\Http\Controllers\Earring\PendientesController;
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


// Route::apiResource('login', AuthController::class);
Route::post('v1/login',[AuthController::class,'login']);

Route::get('v1/pruebas',[AuthController::class,'pruebas']);


Route::group(['middleware' => ['jwt.verify']], function () {
    // Rutas protegidas por tu middleware
    Route::post('v1/logout',[AuthController::class,'logout']);
    Route::apiResource('v1/clientes', ClienteController::class);

Route::get('v1/users/{page?}',[UsersController::class,'index']);
Route::get('v1/user/search', [UsersController::class, 'search']);
Route::post('v1/user',[UsersController::class,'create']);
Route::get('v1/user/{id?}',[UsersController::class,'show']);
Route::put('v1/user/{id?}',[UsersController::class,'update']);
Route::delete('v1/user/{id?}',[UsersController::class,'destroy']);

Route::get('v1/categorias',[CategoriasController::class,'index']);
Route::get('v1/categorias/search', [CategoriasController::class, 'search']);
Route::post('v1/categorias',[CategoriasController::class,'create']);
Route::get('v1/categorias/{id?}',[CategoriasController::class,'show']);
Route::put('v1/categorias/{id?}',[CategoriasController::class,'update']);
Route::delete('v1/categorias/{id?}',[CategoriasController::class,'destroy']);

Route::get('v1/pendientes',[PendientesController::class,'index']);
Route::get('v1/pendientes/search', [PendientesController::class, 'search']);
Route::post('v1/pendientes',[PendientesController::class,'create']);
Route::get('v1/pendientes/{id?}',[PendientesController::class,'show']);
Route::put('v1/pendientes/{id?}',[PendientesController::class,'update']);
Route::delete('v1/pendientes/{id?}',[PendientesController::class,'destroy']);
    
});
