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

// Segundo passo do login: troca o token temporário pelo definitivo
Route::post('mfa/validate-login', [MfaController::class, 'validateLogin']);

Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function () {
    // Perfil do próprio usuário — o id sai do token, não da URL
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);
    Route::post('profile/image', [UserController::class, 'storeProfileImage']);
    Route::delete('profile/image', [UserController::class, 'destroyProfileImage']);

    // MFA do próprio usuário (id vem do token)
    Route::group(['prefix' => 'mfa'], function () {
        Route::post('setup', [MfaController::class, 'setup']);
        Route::post('enable', [MfaController::class, 'enable']);
        Route::post('disable', [MfaController::class, 'disable']);
        Route::post('regenerate-codes', [MfaController::class, 'regenerateBackupCodes']);
    });

    Route::get('users/get', [UserController::class, 'get']);
    Route::get('users/find', [UserController::class, 'find']);
    Route::get('users/paginate', [UserController::class, 'paginate']);
    Route::get('users/export', [UserController::class, 'export']);
    Route::put('users/{id}/restore', [UserController::class, 'restore']);
    Route::resource('users', UserController::class); //users resource

    Route::get('access-levels/get', [AccessLevelController::class, 'get']);
    Route::get('access-levels/find', [AccessLevelController::class, 'find']);
    Route::get('access-levels/paginate', [AccessLevelController::class, 'paginate']);
    Route::put('access-levels/{id}/restore', [AccessLevelController::class, 'restore']);
    Route::resource('access-levels', AccessLevelController::class); //access-levels resource

    Route::get('user-permissions/get', [UserPermissionController::class, 'get']);
    Route::get('user-permissions/find', [UserPermissionController::class, 'find']);

    Route::get('user-permission-categories/get', [UserPermissionCategoryController::class, 'get']);
    Route::get('user-permission-categories/find', [UserPermissionCategoryController::class, 'find']);
});
