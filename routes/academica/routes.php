<?php

Route::group([
    'prefix' => 'niveles-educativos',
    'as' => 'nivel_educativo_',
    'middleware' => ['can:access-resource,"academica"'],
], function(){
    require __DIR__ . '/niveles_educativos.php';
});

Route::group([
    'prefix' => 'secciones',
    'as' => 'seccion_',
    'middleware' => ['can:access-resource,"academica"'],
], function(){
    require __DIR__ . '/secciones.php';
});

Route::group([
    'prefix' => 'grados',
    'as' => 'grado_',
    'middleware' => ['can:access-resource,"academica"'],
], function(){
    require __DIR__ . '/grados.php';
});

Route::group([
    'prefix' => 'cursos',
    'as' => 'curso_',
    'middleware' => ['can:access-resource,"academica"'],
], function(){
    require __DIR__ . '/cursos.php';
});

Route::group([
    'prefix' => 'catedras',
    'as' => 'catedra_',
    'middleware' => ['can:access-resource,"academica"'],
], function(){
    require __DIR__ . '/catedras.php';
});