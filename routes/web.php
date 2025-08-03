<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BiletController;
use App\Http\Controllers\Admin\FilmController;
use App\Http\Controllers\Admin\SalonController;
use App\Http\Controllers\Admin\SeansController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/login');


Route::get('test', function(){
    return response()->json([
        "id"    => 5,
        "title" => "dasd"
    ]);
})->name('logine');
Route::group(['prefix' =>'/'],function(){
     Route::view('login', 'admin.auth.login')
          ->name('login');
     Route::post('login', [AuthController::class, 'login'])
          ->name('login.post');
 });

Route::prefix('admin')
     ->name('admin.')
     ->group(function () {
         
         Route::middleware(['auth:web','admin'])->group(function(){
               Route::view('/', 'admin.index')
                  ->name('dashboard');

             Route::get('seans', [SeansController::class, 'index'])
                  ->name('seans.index');
             Route::get('seans/create', [SeansController::class, 'create'])
                  ->name('seans.create');
             Route::post('seans', [SeansController::class, 'store'])
                  ->name('seans.store');
             Route::get('seans/{seans}', [SeansController::class, 'show'])
                  ->name('seans.show');
             Route::delete('seans/{seans}', [SeansController::class, 'destroy'])
                  ->name('seans.destroy');

             Route::get('films', [FilmController::class, 'index'])
                  ->name('films.index');
             Route::get('films/{film}', [FilmController::class, 'show'])
                  ->name('films.show');
             Route::delete('films/{film}', [FilmController::class, 'destroy'])
                  ->name('films.destroy');

             Route::get('salons', [SalonController::class, 'index'])
                  ->name('salon.index');
             Route::get('salons/{salon}', [SalonController::class, 'show'])
                  ->name('salon.show');
             Route::post('salons/{salon}/toggle-status', [SalonController::class, 'toggleStatus'])
                  ->name('salon.toggle');
             Route::post('salons/{salon}/generate-seats', [SalonController::class, 'generateSeats'])
                  ->name('salons.generateSeats');

             Route::get('bilets', [BiletController::class, 'index'])
                  ->name('bilets.index');
                  Route::get('seans/{seans}/bilets', [BiletController::class, 'showbySeans'])
                  ->name('bilets.showbySeans');
                  // Route::get('bilets/{bilet}', [BiletController::class, 'showbySeans'])
               //  ->name('bilets.showbySeans');
              //Route::get('seans/{seans}/bilets', [BiletController::class, 'showBySeans'])
            //  ->name('bilets.showBySeans');
             Route::patch('bilets/{bilet}/toggle', [BiletController::class, 'toggleStatus'])
                  ->name('bilets.toggle');

             Route::post('logout', [AuthController::class, 'logout'])
                  ->name('logout');
                  Route::post(
                    'seans/{seans}/seat/{seat}/toggle', 
                    [SeansController::class, 'toggleSeat']
                )->name('seans.seat.toggle');  
                 Route::get('users', [UserController::class, 'index'])
             ->name('users.index'); 
             Route::post('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])
             ->name('users.toggleAdmin');
         });

     });
