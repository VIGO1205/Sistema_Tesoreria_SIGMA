<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'reportes-academicos',
    'as' => 'reporte_academico_',
    'middleware' => ['can:access-resource,"reportes"'],
], function(){
    require __DIR__ . '/reportes_academicos.php';
});

Route::group([
    'prefix' => 'reportes-financieros',
    'as' => 'reporte_financiero_',
    'middleware' => ['can:access-resource,"reportes"'],
], function(){
    require __DIR__ . '/reportes_financieros.php';
});