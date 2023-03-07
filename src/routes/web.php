<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
<<<<<<< HEAD
use App\Http\Controllers\CrawlController;
=======
use App\Http\Controllers\CrawlNews;
use App\Http\Controllers\CrawlCategory;
use App\Http\Controllers\CategoryController;

>>>>>>> ac5b240 (update)

Route::get('news',[NewsController::class,'find']);
Route::get('news/{id}',[NewsController::class,'detail'])->where('id','[0-9]+');


<<<<<<< HEAD
Route::get('test',[CrawlController::class,'crawlCategories']);
=======
Route::get('test',[CrawlNews::class,'crawlDataa']);

// Route::get('categories',[CategoryController::class,'find']);
// Route::post('categories/create',[CategoryController::class,'create']);

Route::post('register', 'API\AuthController@register');
Route::post('login', 'API\AuthController@login');
>>>>>>> ac5b240 (update)
