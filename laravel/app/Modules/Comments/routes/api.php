<?php

Route::get('comments/get', 'CommentController@get')             ->name('comments.get');
Route::get('comments/find', 'CommentController@find')           ->name('comments.find');
Route::get('comments/paginate', 'CommentController@paginate')   ->name('comments.paginate');
Route::put('comments/{id}/restore', 'CommentController@restore')->name('comments.restore');
Route::resource('comments', 'CommentController');                     //check-list-comments resource