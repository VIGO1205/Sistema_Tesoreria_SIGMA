<?php

use App\Http\Controllers\ValidacionPagoController;

Route::get('/', [App\Http\Controllers\ValidacionPagoController::class, 'index'])
        ->name('view');

Route::get('/validar/{id_pago}', [App\Http\Controllers\ValidacionPagoController::class, 'validar'])
        ->name('validar');
    
Route::post('/toggle-ia', [App\Http\Controllers\ValidacionPagoController::class, 'toggleIA'])
        ->name('toggle_ia');
    
Route::post('/procesar-ia/{id_pago}', [App\Http\Controllers\ValidacionPagoController::class, 'procesarIA'])
        ->name('procesar_ia');

Route::post('/validar-detalle/{id_detalle}', [App\Http\Controllers\ValidacionPagoController::class, 'validarDetalle'])
        ->name('validar_detalle');

Route::post('/guardar-validaciones/{id_pago}', [App\Http\Controllers\ValidacionPagoController::class, 'guardarValidaciones'])
        ->name('guardar_validaciones');
