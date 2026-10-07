<?php

use App\Modules\Configs\Http\Controllers\ConfigController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'System'], function () {

    Route::get('new-access-level/{category_name}/{category_type}/{unique_permission?}/{unique_name?}', 'ConfigController@new_access_level');
    Route::get('remove-access-level/{category_type}', 'ConfigController@remove_access_level');

    Route::group(['middleware' => ['api']], function () {
        Route::get('configs/get', [ConfigController::class, 'get']);
    });

    Route::group(['middleware' => ['api', 'jwt.auth', 'jwt.refresh']], function () {
        Route::put('configs', 'ConfigController@update');
    });

});

// Route::group(['middleware' => ['api', 'jwt.auth', 'jwt.refresh']], function () {

//     Route::get('config', 'System\ConfigController@get')->name('config.get');

// });
