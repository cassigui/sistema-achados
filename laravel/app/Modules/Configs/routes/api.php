<?php

use App\Modules\Configs\Http\Controllers\ConfigController;

Route::get('configs/get', [ConfigController::class, 'get']);
Route::put('configs', [ConfigController::class, 'update']);
Route::get('new-access-level/{category_name}/{category_type}/{unique_permission?}/{unique_name?}', [ConfigController::class, 'new_access_level']);
Route::get('remove-access-level/{category_type}', [ConfigController::class, 'remove_access_level']);
