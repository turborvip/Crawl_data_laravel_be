<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

<<<<<<< HEAD
=======
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;


>>>>>>> ac5b240 (update)
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

<<<<<<< HEAD
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
=======
Route::get('categories',[CategoryController::class,'find']);
Route::get('categories/findAll',[CategoryController::class,'findAll']);

Route::get('news',[NewsController::class,'find']);
Route::post('news/detail',[NewsController::class,'findDetail']);
Route::get('news/findNewsByCategory/{idCategory}',[NewsController::class,'findNewsByCategory']);
Route::get('news/inHome',[NewsController::class,'findNewsInHome']);

Route::post("login",[UserController::class,'login']);

Route::middleware('auth:sanctum')->group( function () {

    Route::post("user/register",[UserController::class,'register']);
    Route::get("user/find",[UserController::class,'find']);
    Route::post("user/update",[UserController::class,'update']);
    Route::post("user/delete",[UserController::class,'delete']);

    Route::post('categories/create',[CategoryController::class,'create']);
    Route::post('categories/delete',[CategoryController::class,'delete']);
    Route::post('categories/update',[CategoryController::class,'update']);

    Route::post('news/create',[NewsController::class,'create']);
    Route::post('news/update',[NewsController::class,'update']);
    Route::post('news/delete',[NewsController::class,'delete']);
    Route::get('news/amount',[NewsController::class,'amount']);
    Route::post('news/getCate',[NewsController::class,'getCategoriesFlowNewsId']);


    Route::post("logout",[UserController::class,'logout']);

});



>>>>>>> ac5b240 (update)
