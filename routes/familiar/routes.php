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

Route::group([
    'prefix' => 'alumno-traslado',
    'as' => 'familiar_traslado_',
    'middleware' => ['can:access-resource,"traslado"'],
], function(){
    require __DIR__ . '/traslado.php';
});

Route::group([
    'prefix' => 'cambiar-password',
    'as' => 'familiar_cambiar_password_',
    'middleware' => ['can:access-resource,"cambiar_password"'],
], function(){
    require __DIR__ . '/cambiar_password.php';
});
