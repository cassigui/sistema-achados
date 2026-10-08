<?php

use App\Modules\Items\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('dashboard', [ItemController::class, 'dashboard'])->name('dashboard');
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('items', [ItemController::class, 'store'])->name('items.store');

    Route::get('items/{id}', [ItemController::class, 'show'])->name('items.show');
    Route::get('items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    Route::patch('items/{id}/status', [ItemController::class, 'updateStatus'])->name('items.status.update');
});