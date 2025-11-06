<?php

Route::group([
    'prefix' => 'docentes',
    'as' => 'docente_',
    'middleware' => ['can:access-resource,"personal"'],
], function(){
    require __DIR__ . '/docentes.php';
});

Route::group([
    'prefix' => 'departamentos-academicos',
    'as' => 'departamento_academico_',
    'middleware' => ['can:access-resource,"personal"'],
], function(){
    require __DIR__ . '/departamentos_academicos.php';
});