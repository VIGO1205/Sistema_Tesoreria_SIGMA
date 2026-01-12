<?php

Route::group([
    'prefix' => 'periodos-academicos',
    'as' => 'periodo_academico_',
    'middleware' => ['can:access-resource,"configuracion"'],
], function(){
    require __DIR__ . '/periodos_academicos.php';
});