<?php

use Modules\Shop\Controllers\MerchController;
use Illuminate\Support\Facades\Route;

Route::prefix('merch')->group(function () {
    Route::get('/', [MerchController::class, 'index'])->name('merch.index');
    Route::get('/create', [MerchController::class, 'create'])->name('merch.create');
    Route::post('/store', [MerchController::class, 'store'])->name('merch.store');
    Route::get('/edit/{id}', [MerchController::class, 'edit'])->name('merch.edit');
    Route::put('/update/{id}', [MerchController::class, 'update'])->name('merch.update');
    Route::delete('/destroy/{id}', [MerchController::class, 'destroy'])->name('merch.destroy');
}); 