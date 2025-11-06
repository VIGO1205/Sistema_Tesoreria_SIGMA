<?php

Route::group([
    'prefix' => 'conceptos-de-pago',
    'as' => 'concepto_de_pago_',
    'middleware' => ['can:access-resource,"financiera"'],
], function(){
    require __DIR__ . '/conceptos_de_pago.php';
});

Route::group([
    'prefix' => 'pagos',
    'as' => 'pago_',
    'middleware' => ['can:access-resource,"financiera"'],
], function(){
    require __DIR__ . '/pagos.php';
});

Route::group([
    'prefix' => 'deudas',
    'as' => 'deuda_',
    'middleware' => ['can:access-resource,"financiera"'],
], function(){
    require __DIR__ . '/deudas.php';
});

Route::group([
    'prefix' => 'orden-pago',
    'as' => 'orden_pago_',
    'middleware' => ['can:access-resource,"financiera"'],
], function(){
    require __DIR__ . '/orden_pago.php';
});