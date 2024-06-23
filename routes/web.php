<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserCrudController;
use App\Http\Middleware\AuthChecking;
use App\Http\Middleware\UserTypeChecking;

Route::middleware(AuthChecking::class)->group(function () {

    Route::get('/', function () {
        return redirect(route('login'));
    })->name('index');

});

Route::middleware(UserTypeChecking::class)->group(function () {

    Route::middleware('auth')->group(function () {

        Route::prefix('user')->group(function () {

            Route::get('/list', [UserCrudController::class, 'list'])->name('user.list');
            Route::post('/list/table', [UserCrudController::class, 'table'])->name('user.list.table');
            Route::get('/', [UserCrudController::class, 'create'])->name('user.create');
            Route::get('/edit/{id}', [UserCrudController::class, 'edit'])->name('user.edit');
            Route::post('/', [UserCrudController::class, 'store'])->name('user.store');
            Route::put('/edit/', [UserCrudController::class, 'update'])->name('user.update');
            Route::put('/edit/password', [UserCrudController::class, 'password'])->name('user.password');
            Route::delete('/edit/', [UserCrudController::class, 'destroy'])->name('user.destroy');

        });

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    });

});

require __DIR__.'/auth.php';