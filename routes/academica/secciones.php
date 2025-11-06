<?php

use App\Http\Controllers\SeccionController;

Route::get('/', [SeccionController::class,'index'])->name('view');

Route::get('/mas', [SeccionController::class, 'viewAll'])
    ->name('viewAll');

Route::group(['middleware' => ['can:manage-resource,"academica","create"']],
    function(){
        Route::get('/crear', [SeccionController::class,'create'])->name('create'); //cambiar a crear xd
        Route::put('/crear', [SeccionController::class, 'createNewEntry'])->name('createNewEntry');
    }
);

Route::group(['middleware' => ['can:manage-resource,"academica","edit"']], function() {
    Route::get('/{id_grado}/{nombreSeccion}/editar', [SeccionController::class, 'edit'])->name('edit');
    Route::patch('/{id_grado}/{nombreSeccion}/editar', [SeccionController::class, 'editEntry'])->name('editEntry');
});

Route::group(['middleware' => ['can:manage-resource,"academica","delete"']],
    function(){
        Route::delete('/', [SeccionController::class,'delete'])->name('delete');
    }
);

Route::group(['middleware' => ['can:manage-resource,"academica","view_details"']],
    function(){
        Route::get('/{id_grado}/{nombreSeccion}', [SeccionController::class,'view_details'])->name('view_details');
    }
);

Route::group(['middleware' => ['can:manage-resource,"academica","download"']], function(){
    Route::get('/export', [SeccionController::class, 'export'])
        ->name('export');

    Route::redirect('/mas/export', '/grados/export');
});