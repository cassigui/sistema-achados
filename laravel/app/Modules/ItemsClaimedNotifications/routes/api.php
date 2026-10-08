<?php

Route::get('itemsClaimedNotifications/get', 'ItemsClaimedNotificationController@get')             ->name('itemsClaimedNotifications.get');
Route::get('itemsClaimedNotifications/find', 'ItemsClaimedNotificationController@find')           ->name('itemsClaimedNotifications.find');
Route::get('itemsClaimedNotifications/paginate', 'ItemsClaimedNotificationController@paginate')   ->name('itemsClaimedNotifications.paginate');
Route::put('itemsClaimedNotifications/{id}/restore', 'ItemsClaimedNotificationController@restore')->name('itemsClaimedNotifications.restore');
Route::resource('itemsClaimedNotifications', 'ItemsClaimedNotificationController');                     //check-list-itemsClaimedNotifications resource