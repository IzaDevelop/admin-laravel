<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/list', [UserController::class, 'index'])->name('user.list');

Route::get('/create', [UserController::class, 'create'])->name('user.create');
Route::post('/store', [UserController::class, 'store'])->name('user.store');

Route::get('edit/user/{user}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/update/{user}', [UserController::class, 'update'])->name('user.update');

Route::get('/view/user/{user}', [UserController::class, 'show'])->name('user.view');

Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');