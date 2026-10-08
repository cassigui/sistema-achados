<?php

use App\Modules\Account\Http\Controllers\AccessLevelController;
use App\Modules\Account\Http\Controllers\MfaController;
use App\Modules\Account\Http\Controllers\TokenController;
use App\Modules\Account\Http\Controllers\UserController;
use App\Modules\Account\Http\Controllers\UserPermissionCategoryController;
use App\Modules\Account\Http\Controllers\UserPermissionController;

// Route::post('users/social-authenticate', [TokenController::class, 'social_authenticate']);
Route::post('users/authenticate', [TokenController::class, 'authenticate']);
Route::post('users/forgot-password', [UserController::class, 'sendResetLinkEmail']);
Route::post('users/validate-reset-token', [UserController::class, 'validateResetToken']);
Route::put('users/update-password', [UserController::class, 'updatePassword']);
Route::post('users/validate-login', [TokenController::class, 'validateLogin']);
Route::get('users/validate-token', [TokenController::class, 'validateToken']);
Route::get('users/check-email', [UserController::class, 'checkEmailAvailability']);

