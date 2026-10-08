<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('account::auth.login.login_page');
});
