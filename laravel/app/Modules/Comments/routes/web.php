<?php

use App\Modules\Comments\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::post('comments/{item_id}', [CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});