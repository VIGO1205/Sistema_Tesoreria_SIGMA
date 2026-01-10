<?php

use App\Http\Controllers\ComposicionFamiliarController;

Route::get('/', [ComposicionFamiliarController::class, 'index'])
    ->name('view');

Route::get('/mas', [ComposicionFamiliarController::class, 'viewAll'])
    ->name('viewAll');

Route::group(['middleware' => ['can:manage-resource,"alumnos","create"']], function(){
    Route::get('/crear', [ComposicionFamiliarController::class, 'create'])
        ->name('create');

    Route::post('/crear', [ComposicionFamiliarController::class, 'store'])
        ->name('store');
});

Route::group(['middleware' => ['can:manage-resource,"alumnos","delete"']], function(){
    Route::delete('/', [ComposicionFamiliarController::class, 'delete'])
        ->name('delete');
});
