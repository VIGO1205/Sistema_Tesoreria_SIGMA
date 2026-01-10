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

Route::group([
    'prefix' => 'traslados',
    'as' => 'traslado_',
    'middleware' => ['can:access-resource,"alumnos"'],
], function(){
    require __DIR__ . '/traslados.php';
});

Route::group([
    'prefix' => 'composiciones-familiares',
    'as' => 'composicion_familiar_',
    'middleware' => ['can:access-resource,"alumnos"'],
], function(){
    require __DIR__ . '/composiciones_familiares.php';
});
