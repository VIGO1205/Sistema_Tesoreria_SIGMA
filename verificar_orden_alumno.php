<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE ORDEN DE PAGO ===\n\n";

$codigo = '478114';
$alumno = App\Models\Alumno::where('codigo_educando', $codigo)->first();

if (!$alumno) {
    echo "❌ Alumno no encontrado\n";
    exit;
}

echo "✅ Alumno encontrado:\n";
echo "   ID: {$alumno->id_alumno}\n";
echo "   Código: {$alumno->codigo_educando}\n";
echo "   Nombre: {$alumno->primer_nombre} {$alumno->apellido_paterno}\n\n";

// Buscar órdenes de pago
$ordenes = DB::table('ordenes_pago')
    ->where('id_alumno', $alumno->id_alumno)
    ->get();

echo "Órdenes de pago totales: " . $ordenes->count() . "\n\n";

if ($ordenes->count() > 0) {
    foreach ($ordenes as $orden) {
        echo "   - Orden ID: {$orden->id_orden}\n";
        echo "     Código: {$orden->codigo_orden}\n";
        echo "     Estado: {$orden->estado}\n";
        echo "     Fecha: {$orden->fecha_orden_pago}\n\n";
    }
    
    // Buscar orden pendiente
    $ordenPendiente = DB::table('ordenes_pago')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('estado', 'pendiente')
        ->orderBy('fecha_orden_pago', 'desc')
        ->first();
    
    if ($ordenPendiente) {
        echo "✅ TIENE ORDEN PENDIENTE:\n";
        echo "   Código: {$ordenPendiente->codigo_orden}\n";
        echo "   Monto Total: {$ordenPendiente->monto_total}\n";
        echo "   Monto Pendiente: {$ordenPendiente->monto_pendiente}\n";
    } else {
        echo "⚠️ NO tiene orden pendiente (solo pagadas/canceladas)\n";
    }
} else {
    echo "❌ Este alumno NO tiene ninguna orden de pago registrada\n";
    echo "   Debe generar una orden de pago antes de poder registrar un pago.\n";
}
