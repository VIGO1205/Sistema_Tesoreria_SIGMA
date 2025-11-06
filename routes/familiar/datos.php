<?php

use App\Http\Controllers\FamiliarDatosController;

Route::get('/', [FamiliarDatosController::class, 'index'])
    ->name('view');
