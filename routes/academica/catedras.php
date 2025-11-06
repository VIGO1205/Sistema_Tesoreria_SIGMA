<?php

use App\Http\Controllers\CatedraController;

Route::get('/', [CatedraController::class, 'index'])
    ->name('view'); 

Route::group(['middleware' => ['can:manage-resource,"academica","create"']], function(){
    Route::get('/crear', [CatedraController::class, 'create'])
        ->name('create');

    Route::put('/crear', [CatedraController::class, 'createNewEntry'])
        ->name('createNewEntry');
});

Route::group(['middleware' => ['can:manage-resource,"academica","edit"']], function(){
    Route::get('/{id}/editar', [CatedraController::class, 'edit'])
        ->name('edit');

    Route::patch('/{id}/editar', [CatedraController::class, 'editEntry'])
        ->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"academica","delete"']], function(){
    Route::delete('/', [CatedraController::class, 'delete'])
        ->name('delete');
});

Route::group(['middleware' => ['can:manage-resource,"alumnos","download"']], function(){
    Route::get('/export', [CatedraController::class, 'export'])
        ->name('export');

    Route::redirect('/mas/export', '/catedras/export');
});
