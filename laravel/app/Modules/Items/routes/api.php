<?php

Route::get('items/get', 'ItemController@get')             ->name('items.get');
Route::get('items/find', 'ItemController@find')           ->name('items.find');
Route::get('items/paginate', 'ItemController@paginate')   ->name('items.paginate');
Route::put('items/{id}/restore', 'ItemController@restore')->name('items.restore');
Route::resource('items', 'ItemController');                     //check-list-items resource