<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\WordController;
use App\Models\Word;

Route::post('login',[AuthController::class,'login']);
Route::get('download',[VideoController::class,'download']);
Route::get('video',[VideoController::class,'video']);
Route::get('edit',[WordController::class,'edit']);
Route::get('editword',[EditController::class,'edit']);
Route::get('countwords',[VideoController::class,'count']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('logout',[AuthController::class,'logOut']);
    Route::get('getme',[AuthController::class,'getme']);

    Route::post('upload',[WordController::class, 'extractPDF']);
    Route::get('count',[WordController::class, 'count']);
});
