<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VideoController;



Route::post('login',[AuthController::class,'login']);
Route::get('download',[VideoController::class,'download']);
Route::get('video',[VideoController::class,'video']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('logout',[AuthController::class,'logOut']);
    Route::get('getme',[AuthController::class,'getme']);
    Route::post('upload',[VideoController::class,'store']);
});
