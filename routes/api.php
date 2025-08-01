<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiletController;
use App\Http\Controllers\FilmController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmSearchController;
use App\Http\Controllers\KoltukController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\SeansController;

Route::post("/register",[AuthController::class,"register"]);
Route::post("/login",[AuthController::class,"login"]);//->name('login');

Route::middleware("auth:sanctum")->group(function(){
Route::post("/logout",[AuthController::class,"logout"]);
Route::get("/me",[AuthController::class,"me"]);

});
Route::get('/films/search', [FilmSearchController::class, 'show']);
Route::get('/films', [FilmController::class, 'index']);                 
Route::get('/films/{id}',[FilmController::class,'show']);            
Route::get('/films/title/{title}', [FilmController::class, 'showByTitle']);


Route::post('forgot-password',   [AuthController::class,'forgotPassword']);
Route::post('reset-password',    [AuthController::class,'resetPassword']);
    Route::get('/seans', [SeansController::class, 'index']);
    Route::get('/seans/{seans}', [SeansController::class, 'show']);
    Route::get('/seans/{seans}/koltuklar', [KoltukController::class, 'show']);


     Route::get('/salon',          [SalonController::class, 'index']);
    Route::get('/salon/{salon}',  [SalonController::class, 'show']);

    Route::get("biletler",[BiletController::class,"index"]);
    Route::get("biletler/history",[BiletController::class,"history"]);
    Route::get("biletler/{id}",[BiletController::class,"show"]);
    Route::delete("biletler/{id}",[BiletController::class,"destroy"]);
    
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/biletler', [BiletController::class, 'store']);



Route::get('films/{film}/seanslar', [SeansController::class, 'byFilm']);
