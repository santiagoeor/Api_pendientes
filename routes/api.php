<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Auth\AuthController;

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


Route::post('v1/login',[AuthController::class,'login']);


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('v1/logout',[AuthController::class,'logout']);
});
Route::get('v1/users/{page?}',[UsersController::class,'index']);
Route::post('v1/user',[UsersController::class,'create']);
Route::get('v1/user/{id?}',[UsersController::class,'show']);
Route::put('v1/user/{id?}',[UsersController::class,'update']);
Route::delete('v1/user/{id?}',[UsersController::class,'destroy']);
