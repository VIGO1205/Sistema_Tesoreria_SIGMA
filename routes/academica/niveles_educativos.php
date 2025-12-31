<?php

use App\Http\Controllers\NivelEducativoController;

Route::get('/', [NivelEducativoController::class, 'index'])
    ->name('view');

Route::get('/mas', [NivelEducativoController::class, 'viewAll'])
    ->name('viewAll');

Route::group(['middleware' => ['can:manage-resource,"academica","create"']], function () {
    Route::get('/crear', [NivelEducativoController::class, 'create'])
        ->name('create');

    Route::put('/crear', [NivelEducativoController::class, 'createNewEntry'])
        ->name('createNewEntry');
});

Route::group(['middleware' => ['can:manage-resource,"academica","edit"']], function () {
    Route::get('/{id}/editar', [NivelEducativoController::class, 'edit'])
        ->name('edit');

    Route::patch('/{id}/editar', [NivelEducativoController::class, 'editEntry'])
        ->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"academica","delete"']], function () {
    Route::delete('/', [NivelEducativoController::class, 'delete'])
        ->name('delete');
});

Route::group(['middleware' => ['can:manage-resource,"academica","download"']], function () {
    Route::get('/export', [NivelEducativoController::class, 'export'])
        ->name('export');

    Route::redirect('/mas/export', '/niveles-educativos/export');
});
