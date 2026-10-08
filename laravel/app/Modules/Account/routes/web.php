<?php
use App\Modules\Account\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [UserController::class, 'loginPage'])->name('login');
Route::get('register', [UserController::class, 'registerPage'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login.post');
Route::post('logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/register', [UserController::class, 'register'])->name('register.submit');