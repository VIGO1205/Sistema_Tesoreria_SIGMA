<?php

use App\Http\Controllers\CursoController;

Route::get('/', [CursoController::class, 'index'])
    ->name('view'); 

Route::get('/mas', [CursoController::class, 'viewAll'])
    ->name('viewAll');

Route::group(['middleware' => ['can:manage-resource,"academica","create"']], function(){
    Route::get('/crear', [CursoController::class, 'create'])
        ->name('create');

    Route::put('/crear', [CursoController::class, 'createNewEntry'])
        ->name('createNewEntry');
});

Route::group(['middleware' => ['can:manage-resource,"academica","edit"']], function(){
    Route::get('/{id}/editar', [CursoController::class, 'edit'])
        ->name('edit');

    Route::patch('/{id}/editar', [CursoController::class, 'editEntry'])
        ->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"academica","delete"']], function(){
    Route::delete('/', [CursoController::class, 'delete'])
        ->name('delete');
});

Route::group(['middleware' => ['can:manage-resource,"academica","download"']], function(){
    Route::get('/export', [CursoController::class, 'export'])
        ->name('export');

    Route::redirect('/mas/export', '/cursos/export');
});