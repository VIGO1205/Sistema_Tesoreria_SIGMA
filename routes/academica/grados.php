<?php

use App\Http\Controllers\GradoController;

Route::get('/', [GradoController::class,'index'])->name('view');

Route::get('/mas', [GradoController::class, 'viewAll'])
    ->name('viewAll');

Route::group(['middleware' => ['can:manage-resource,"academica","create"']],
    function(){
        Route::get('/crear', [GradoController::class,'create'])->name('create'); //cambiar a crear xd
        Route::put('/crear', [GradoController::class, 'createNewEntry'])->name('createNewEntry');
    }
);

Route::group(['middleware' => ['can:manage-resource,"academica","edit"']],
    function(){
        Route::get('/{id}/editar', [GradoController::class,'edit'])->name('edit');
        Route::patch('/{id}/editar', [GradoController::class, 'editEntry'])->name('editEntry');
    }
);

Route::group(['middleware' => ['can:manage-resource,"academica","delete"']],
    function(){
        Route::delete('/', [GradoController::class,'delete'])->name('delete');
    }
);

Route::group(['middleware' => ['can:manage-resource,"academica","view_details"']],
    function(){
        Route::get('/{id}/view_details', [GradoController::class,'view_details'])->name('view_details');
    }
);

Route::group(['middleware' => ['can:manage-resource,"academica","download"']], function(){
    Route::get('/export', [GradoController::class, 'export'])
        ->name('export');

    Route::redirect('/mas/export', '/grados/export');
});