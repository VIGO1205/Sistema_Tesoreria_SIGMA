<?php

use App\Http\Controllers\FamiliarPagosController;

Route::get('/', [FamiliarPagosController::class, 'indexPagos'])
    ->name('view_pagos');

Route::get('/deudas', [FamiliarPagosController::class, 'indexDeudas'])
    ->name('view_deudas');