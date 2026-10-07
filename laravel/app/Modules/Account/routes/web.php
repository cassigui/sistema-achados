<?php
use App\Modules\Account\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [UserController::class, 'loginPage'])->name('login');
Route::get('register', [UserController::class, 'registerPage'])->name('register');
