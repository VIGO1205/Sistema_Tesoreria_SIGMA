<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudTrasladoController;

Route::get('/', [SolicitudTrasladoController::class, 'index'])->name('view');
Route::post('/buscar', [SolicitudTrasladoController::class, 'buscarAlumno'])->name('buscar');
Route::post('/guardar', [SolicitudTrasladoController::class, 'guardarSolicitud'])->name('guardar');
Route::get('/pdf/{codigo_solicitud}', [SolicitudTrasladoController::class, 'generarPDF'])->name('pdf');
Route::get('/listar', [SolicitudTrasladoController::class, 'listarSolicitudes'])->name('listar');
