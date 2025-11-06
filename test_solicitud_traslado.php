<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Alumno;
use App\Models\Deuda;
use App\Models\SolicitudTraslado;

echo "=== PRUEBA DEL MÓDULO DE SOLICITUD DE TRASLADO ===\n\n";

// 1. Buscar alumnos
echo "1. Buscando alumnos en la base de datos...\n";
$totalAlumnos = Alumno::count();
echo "   Total de alumnos: {$totalAlumnos}\n\n";

if ($totalAlumnos > 0) {
    // 2. Buscar un alumno sin deudas
    echo "2. Buscando alumnos sin deudas pendientes...\n";
    $alumnosSinDeudas = Alumno::whereDoesntHave('deudas', function($query) {
        $query->where('estado', 0);
    })->take(5)->get();

    echo "   Alumnos sin deudas encontrados: " . $alumnosSinDeudas->count() . "\n";

    if ($alumnosSinDeudas->count() > 0) {
        foreach ($alumnosSinDeudas as $alumno) {
            echo "   - Código: {$alumno->codigo_educando} | {$alumno->primer_nombre} {$alumno->apellido_paterno}\n";
        }
    }
    echo "\n";

    // 3. Buscar alumnos con deudas
    echo "3. Buscando alumnos con deudas pendientes...\n";
    $alumnosConDeudas = Alumno::whereHas('deudas', function($query) {
        $query->where('estado', 0);
    })->take(5)->get();

    echo "   Alumnos con deudas encontrados: " . $alumnosConDeudas->count() . "\n";

    if ($alumnosConDeudas->count() > 0) {
        foreach ($alumnosConDeudas as $alumno) {
            $totalDeuda = $alumno->deudas()->where('estado', 0)->count();
            echo "   - Código: {$alumno->codigo_educando} | {$alumno->primer_nombre} {$alumno->apellido_paterno} | Deudas: {$totalDeuda}\n";
        }
    }
    echo "\n";

    // 4. Verificar tabla de solicitudes
    echo "4. Verificando tabla de solicitudes de traslado...\n";
    $totalSolicitudes = SolicitudTraslado::count();
    echo "   Total de solicitudes registradas: {$totalSolicitudes}\n\n";

    if ($totalSolicitudes > 0) {
        echo "   Últimas 5 solicitudes:\n";
        $solicitudes = SolicitudTraslado::with('alumno')->orderBy('fecha_solicitud', 'desc')->take(5)->get();
        foreach ($solicitudes as $solicitud) {
            echo "   - {$solicitud->codigo_solicitud} | {$solicitud->alumno->primer_nombre} {$solicitud->alumno->apellido_paterno} | Estado: {$solicitud->estado}\n";
        }
    }

    // 5. Test de rutas
    echo "\n5. Rutas del módulo de traslado:\n";
    echo "   - Vista principal: " . route('traslado_view') . "\n";
    echo "   - Buscar alumno: " . route('traslado_buscar') . " (POST)\n";
    echo "   - Guardar solicitud: " . route('traslado_guardar') . " (POST)\n";
    echo "   - Listar solicitudes: " . route('traslado_listar') . "\n";
    echo "   - Generar PDF: " . url('traslados/pdf/{codigo}') . "\n";

} else {
    echo "   ⚠️ No hay alumnos en la base de datos.\n";
}

echo "\n=== FIN DE LA PRUEBA ===\n";
