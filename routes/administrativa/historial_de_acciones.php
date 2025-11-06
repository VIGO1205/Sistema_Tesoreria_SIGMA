<?php

use App\Http\Controllers\HistorialAccionesController;

Route::get('/', [HistorialAccionesController::class, 'index'])
        ->name('view');
