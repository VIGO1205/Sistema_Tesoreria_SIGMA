<?php

use App\Http\Controllers\FamiliarMatriculasController;

Route::get('/', [FamiliarMatriculasController::class, 'index'])
    ->name('view');