<?php

Route::group([
    'prefix' => 'alumnos',
    'as' => 'alumno_',
    'middleware' => ['can:access-resource,"alumnos"'],
], function(){
    require __DIR__ . '/alumnos.php';
});

Route::group([
    'prefix' => 'matriculas',
    'as' => 'matricula_',
    'middleware' => ['can:access-resource,"alumnos"'],
], function(){
    require __DIR__ . '/matriculas.php';
});

Route::group([
    'prefix' => 'familiares',
    'as' => 'familiar_',
    'middleware' => ['can:access-resource,"alumnos"'],
], function(){
    require __DIR__ . '/familiares.php';
});