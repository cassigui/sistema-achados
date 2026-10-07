<?php

Route::get('users/get', 'UserController@get')             ->name('users.get');
Route::get('users/find', 'UserController@find')           ->name('users.find');
Route::get('users/paginate', 'UserController@paginate')   ->name('users.paginate');
Route::put('users/{id}/restore', 'UserController@restore')->name('users.restore');
Route::resource('users', 'UserController');                     //users resource