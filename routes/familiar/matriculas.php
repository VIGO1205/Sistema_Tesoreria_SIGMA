<?php

use App\Http\Controllers\FamiliarMatriculasController;

Route::get('/', [FamiliarMatriculasController::class, 'index'])->name('view');
Route::get('/prematricula', [FamiliarMatriculasController::class, 'create'])->name('prematricula_create');
Route::post('/prematricula', [FamiliarMatriculasController::class, 'store'])->name('prematricula_store');