<?php

use App\Http\Controllers\ConceptoPagoController;

Route::get('/', [ConceptoPagoController::class, 'index'])
    ->name('view');

Route::group(['middleware' => ['can:manage-resource,"financiera","create"']], function () {
    Route::get('/crear', [ConceptoPagoController::class, 'create'])
        ->name('create');

    Route::put('/crear', [ConceptoPagoController::class, 'createNewEntry'])
        ->name('createNewEntry');
});

Route::group(['middleware' => ['can:manage-resource,"financiera","edit"']], function () {
    Route::get('/{id}/editar', [ConceptoPagoController::class, 'edit'])
        ->name('edit');

    Route::patch('/{id}/editar', [ConceptoPagoController::class, 'editEntry'])
        ->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"financiera","delete"']], function () {
    Route::delete('/', [ConceptoPagoController::class, 'delete'])
        ->name('delete');
});
