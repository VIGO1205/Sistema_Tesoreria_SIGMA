<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasarelaPagoController;

require __DIR__ . '/auth/routes.php';

Route::get('/pagar', [PasarelaPagoController::class, 'index'])->name('pasarela.index');

Route::get('/pagar/{codigo_orden}', [PasarelaPagoController::class, 'mostrarOrden'])->name('pasarela.orden');

Route::get('/pagar/{codigo_orden}/metodo/{metodo}', [PasarelaPagoController::class, 'mostrarMetodoPago'])->name('pasarela.metodo');

Route::post('/pagar/{codigo_orden}/procesar', [PasarelaPagoController::class, 'procesarPago'])->name('pasarela.procesar');

Route::get('/pagar/{codigo_orden}/comprobante/{transaccion_id}', [PasarelaPagoController::class, 'mostrarComprobante'])->name('pasarela.comprobante');

Route::get('/pagar/voucher/{transaccion_id}/pdf', [PasarelaPagoController::class, 'descargarVoucherPDF'])->name('pasarela.voucher.pdf');

Route::get('/pagar/voucher/{transaccion_id}/html', [PasarelaPagoController::class, 'mostrarVoucherHTML'])->name('pasarela.voucher.html');

Route::middleware(['auth'])->group(function(){
    require __DIR__ . '/academica/routes.php';
    require __DIR__ . '/alumnos/routes.php';
    require __DIR__ . '/administrativa/routes.php';
    require __DIR__ . '/financiera/routes.php';
    require __DIR__ . '/personal/routes.php';
    require __DIR__ . '/reportes/routes.php';
    require __DIR__ . '/familiar/routes.php';

    Route::get('/tests', [\App\Http\Controllers\Tests\CRUDTestController::class, 'index']);
});