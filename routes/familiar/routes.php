<?php

Route::group([
    'prefix' => 'alumno-matriculas',
    'as' => 'familiar_matricula_',
    'middleware' => ['can:access-resource,"matriculas"'],
], function(){
    require __DIR__ . '/matriculas.php';
});

Route::group([
    'prefix' => 'alumno-datos',
    'as' => 'familiar_dato_',
    'middleware' => ['can:access-resource,"datos"'],
], function(){
    require __DIR__ . '/datos.php';
});

Route::group([
    'prefix' => 'alumno-pagos',
    'as' => 'familiar_pago_',
    'middleware' => ['can:access-resource,"pagos"'],
], function(){
    require __DIR__ . '/pagos.php';
});