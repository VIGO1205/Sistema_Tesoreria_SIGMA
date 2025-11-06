<?php

use App\Http\Controllers\DeudaController;

Route::get('/', [DeudaController::class, 'index'])
    ->name('view');

Route::group(['middleware' => ['can:manage-resource,"financiera","create"']], function () {
    Route::get('/crear', [DeudaController::class, 'create'])
        ->name('create');

    Route::put('/crear', [DeudaController::class, 'createNewEntry'])
        ->name('createNewEntry');
});

Route::group(['middleware' => ['can:manage-resource,"financiera","edit"']], function () {
    Route::get('/{id}/editar', [DeudaController::class, 'edit'])
        ->name('edit');

    Route::patch('/{id}/editar', [DeudaController::class, 'editEntry'])
        ->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"financiera","delete"']], function () {
    Route::delete('/', [DeudaController::class, 'delete'])
        ->name('delete');
});
