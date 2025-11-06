<?php

use App\Http\Controllers\FamiliarController;

Route::get('/', [FamiliarController::class, 'index'])
    ->name('view');

Route::get('/mas', [FamiliarController::class, 'viewAll'])
    ->name('viewAll');

Route::group(['middleware' => ['can:manage-resource,"alumnos","create"']], function(){
    Route::get('/crear', [FamiliarController::class, 'create'])
        ->name('create');

    Route::put('/crear', [FamiliarController::class, 'createNewEntry'])
        ->name('createNewEntry');
});

Route::group(['middleware' => ['can:manage-resource,"alumnos","edit"']], function(){
    Route::get('/{id}/editar', [FamiliarController::class, 'edit'])
        ->name('edit');

    Route::patch('/{id}/editar', [FamiliarController::class, 'editEntry'])
        ->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"alumnos","delete"']], function(){
    Route::delete('/', [FamiliarController::class, 'delete'])
        ->name('delete');
});

Route::group(['middleware' => ['can:manage-resource,"alumnos","view_details"']], function(){
    Route::get('/{id}/detalles', [FamiliarController::class, 'viewDetalles'])
        ->name('detalles');
});

Route::group(['middleware' => ['can:manage-resource,"alumnos","download"']], function(){
    Route::get('/export', [FamiliarController::class, 'export'])
        ->name('export');

    Route::redirect('/mas/export', '/niveles-educativos/export');
});
