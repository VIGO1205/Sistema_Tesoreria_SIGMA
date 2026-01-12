<?php

use App\Http\Controllers\PeriodoAcademicoController;

Route::get('/', [PeriodoAcademicoController::class, 'index'])
    ->name('view');

Route::get('/mas', [PeriodoAcademicoController::class, 'viewAll'])
    ->name('viewAll');

Route::group(['middleware' => ['can:manage-resource,"administrativa","create"']], function () {
    Route::get('/crear', [PeriodoAcademicoController::class, 'create'])
        ->name('create');

    Route::put('/crear', [PeriodoAcademicoController::class, 'createNewEntry'])
        ->name('createNewEntry');
});

Route::group(['middleware' => ['can:manage-resource,"administrativa","edit"']], function () {
    Route::get('/{id}/editar', [PeriodoAcademicoController::class, 'edit'])
        ->name('edit');

    Route::patch('/{id}/editar', [PeriodoAcademicoController::class, 'editEntry'])
        ->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"administrativa","delete"']], function () {
    Route::delete('/', [PeriodoAcademicoController::class, 'delete'])
        ->name('delete');
});

// Ruta especial: Solo Director puede establecer período actual
Route::group(['middleware' => ['can:manage-resource,"administrativa","edit"']], function () {
    Route::post('/{id}/establecer-actual', [PeriodoAcademicoController::class, 'establecerActual'])
        ->name('establecerActual');
});

Route::group(['middleware' => ['can:manage-resource,"administrativa","download"']], function () {
    Route::get('/export', [PeriodoAcademicoController::class, 'export'])
        ->name('export');

    Route::redirect('/mas/export', '/periodos-academicos/export');
});