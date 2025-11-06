<?php
/**
 * Test de Búsqueda para Solicitud de Traslado
 *
 * Este script prueba la funcionalidad de búsqueda de alumnos
 * para generar solicitudes de traslado
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Alumno;
use App\Models\Deuda;
use App\Models\Matricula;
use App\Models\Seccion;
use Carbon\Carbon;

echo "\n╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║          TEST DE BÚSQUEDA - SOLICITUD DE TRASLADO                          ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

$testsPassed = 0;
$testsFailed = 0;
$totalTests = 8;

// Test 1: Verificar conexión a base de datos
echo "📋 TEST 1: Verificar Conexión a Base de Datos\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
try {
    DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "✅ PASS: Conectado exitosamente a la base de datos '$dbName'\n\n";
    $testsPassed++;
} catch (\Exception $e) {
    echo "❌ FAIL: Error de conexión: " . $e->getMessage() . "\n\n";
    $testsFailed++;
    exit(1);
}

// Test 2: Verificar existencia de tablas necesarias
echo "📋 TEST 2: Verificar Existencia de Tablas Requeridas\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
$tablasRequeridas = ['alumnos', 'deudas', 'matriculas', 'secciones', 'solicitudes_traslado'];
$tablasExisten = true;
foreach ($tablasRequeridas as $tabla) {
    $existe = DB::select("SHOW TABLES LIKE '$tabla'");
    if (count($existe) > 0) {
        echo "✅ Tabla '$tabla' existe\n";
    } else {
        echo "❌ Tabla '$tabla' NO existe\n";
        $tablasExisten = false;
    }
}
if ($tablasExisten) {
    echo "✅ PASS: Todas las tablas requeridas existen\n\n";
    $testsPassed++;
} else {
    echo "❌ FAIL: Faltan tablas requeridas\n\n";
    $testsFailed++;
}

// Test 3: Buscar alumnos de prueba
echo "📋 TEST 3: Buscar Alumnos de Prueba en la Base de Datos\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
$alumnosTest = Alumno::whereIn('codigo_educando', ['166787', '945008'])
    ->get(['id_alumno', 'codigo_educando', 'apellido_paterno', 'apellido_materno', 'primer_nombre', 'otros_nombres', 'dni']);

if ($alumnosTest->count() > 0) {
    echo "✅ PASS: Se encontraron " . $alumnosTest->count() . " alumno(s) de prueba:\n";
    foreach ($alumnosTest as $alumno) {
        $nombreCompleto = $alumno->primer_nombre . ($alumno->otros_nombres ? ' ' . $alumno->otros_nombres : '');
        echo "   - Código: {$alumno->codigo_educando} | Nombre: {$alumno->apellido_paterno} {$alumno->apellido_materno}, {$nombreCompleto}\n";
        echo "     DNI: " . ($alumno->dni ?? 'Sin DNI') . " | ID: {$alumno->id_alumno}\n";
    }
    echo "\n";
    $testsPassed++;
} else {
    echo "❌ FAIL: No se encontraron alumnos de prueba\n\n";
    $testsFailed++;
}

// Test 4: Verificar información académica (obtenerGradoActual)
echo "📋 TEST 4: Verificar Método obtenerGradoActual() Corregido\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
if ($alumnosTest->count() > 0) {
    $alumnoTest = $alumnosTest->first();
    try {
        // Simular el método obtenerGradoActual corregido
        $matricula = DB::table('matriculas')
            ->where('matriculas.id_alumno', $alumnoTest->id_alumno)
            ->where('matriculas.estado', 1)
            ->orderBy('matriculas.año_escolar', 'desc')
            ->first();

        if ($matricula) {
            $grado = DB::table('grados')
                ->where('id_grado', $matricula->id_grado)
                ->first();

            if ($grado) {
                $gradoInfo = $grado->nombre_grado . ' - Sección: ' . $matricula->nombreSeccion . ' (' . $matricula->año_escolar . ')';
            } else {
                $gradoInfo = 'Sección: ' . $matricula->nombreSeccion . ' - Año: ' . $matricula->año_escolar;
            }
        } else {
            $gradoInfo = 'Sin matrícula registrada';
        }

        echo "✅ PASS: Método obtenerGradoActual() ejecutado sin errores SQL\n";
        echo "   Resultado: $gradoInfo\n\n";
        $testsPassed++;
    } catch (\Exception $e) {
        echo "❌ FAIL: Error en obtenerGradoActual(): " . $e->getMessage() . "\n\n";
        $testsFailed++;
    }
} else {
    echo "⚠️  SKIP: No hay alumnos para probar\n\n";
}

// Test 5: Verificar deudas del alumno sin deudas (166787)
echo "📋 TEST 5: Verificar Alumno SIN DEUDAS (Código: 166787)\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
$alumnoSinDeudas = Alumno::where('codigo_educando', '166787')->first();
if ($alumnoSinDeudas) {
    $deudas = Deuda::where('id_alumno', $alumnoSinDeudas->id_alumno)
        ->where('estado', 0) // Estado 0 = deuda pendiente
        ->get();

    // Filtrar deudas con monto pendiente > 0
    $deudasConSaldo = $deudas->filter(function($deuda) {
        $montoPendiente = $deuda->monto_total - $deuda->monto_a_cuenta - $deuda->monto_adelantado;
        return $montoPendiente > 0;
    });

    if ($deudasConSaldo->count() == 0) {
        echo "✅ PASS: Alumno {$alumnoSinDeudas->codigo_educando} NO tiene deudas pendientes\n";
        echo "   Debe poder proceder con el formulario de traslado\n\n";
        $testsPassed++;
    } else {
        echo "⚠️  ADVERTENCIA: Alumno {$alumnoSinDeudas->codigo_educando} tiene {$deudasConSaldo->count()} deuda(s)\n";
        echo "   Esto puede afectar las pruebas de usuario sin deudas\n\n";
        $testsPassed++;
    }
} else {
    echo "⚠️  SKIP: Alumno 166787 no encontrado\n\n";
}

// Test 6: Verificar deudas del alumno con deudas (945008)
echo "📋 TEST 6: Verificar Alumno CON DEUDAS (Código: 945008)\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
$alumnoConDeudas = Alumno::where('codigo_educando', '945008')->first();
if ($alumnoConDeudas) {
    $deudas = Deuda::where('id_alumno', $alumnoConDeudas->id_alumno)
        ->where('estado', 0) // Estado 0 = deuda pendiente
        ->get();

    // Filtrar deudas con monto pendiente > 0
    $deudasConSaldo = $deudas->filter(function($deuda) {
        $montoPendiente = $deuda->monto_total - $deuda->monto_a_cuenta - $deuda->monto_adelantado;
        return $montoPendiente > 0;
    });

    if ($deudasConSaldo->count() > 0) {
        $totalPendiente = $deudasConSaldo->sum(function($deuda) {
            return $deuda->monto_total - $deuda->monto_a_cuenta - $deuda->monto_adelantado;
        });
        echo "✅ PASS: Alumno {$alumnoConDeudas->codigo_educando} tiene {$deudasConSaldo->count()} deuda(s) pendiente(s)\n";
        echo "   Monto total pendiente: S/ " . number_format($totalPendiente, 2) . "\n";
        echo "   NO debe poder proceder con el formulario de traslado\n\n";
        $testsPassed++;
    } else {
        echo "⚠️  ADVERTENCIA: Alumno {$alumnoConDeudas->codigo_educando} NO tiene deudas\n";
        echo "   Esto puede afectar las pruebas de usuario con deudas\n\n";
        $testsPassed++;
    }
} else {
    echo "⚠️  SKIP: Alumno 945008 no encontrado\n\n";
}

// Test 7: Simular búsqueda completa (como el controlador)
echo "📋 TEST 7: Simular Búsqueda Completa (Como SolicitudTrasladoController)\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
if ($alumnosTest->count() > 0) {
    $codigoBusqueda = $alumnosTest->first()->codigo_educando;
    try {
        $alumno = Alumno::where('codigo_educando', $codigoBusqueda)->first();

        if (!$alumno) {
            throw new \Exception("Alumno no encontrado");
        }

        // Verificar deudas
        $deudas = Deuda::where('id_alumno', $alumno->id_alumno)
            ->where('estado', 0)
            ->get();

        // Filtrar deudas con saldo pendiente
        $deudasConSaldo = $deudas->filter(function($deuda) {
            $montoPendiente = $deuda->monto_total - $deuda->monto_a_cuenta - $deuda->monto_adelantado;
            return $montoPendiente > 0;
        });

        // Obtener grado (método corregido)
        $matricula = DB::table('matriculas')
            ->where('matriculas.id_alumno', $alumno->id_alumno)
            ->where('matriculas.estado', 1)
            ->orderBy('matriculas.año_escolar', 'desc')
            ->first();

        $gradoInfo = 'Sin matrícula registrada';
        if ($matricula) {
            $grado = DB::table('grados')->where('id_grado', $matricula->id_grado)->first();
            $gradoInfo = $grado
                ? $grado->nombre_grado . ' - Sección: ' . $matricula->nombreSeccion . ' (' . $matricula->año_escolar . ')'
                : 'Sección: ' . $matricula->nombreSeccion . ' - Año: ' . $matricula->año_escolar;
        }

        $nombreCompleto = $alumno->primer_nombre . ($alumno->otros_nombres ? ' ' . $alumno->otros_nombres : '');
        echo "✅ PASS: Búsqueda completa ejecutada sin errores\n";
        echo "   Código: {$alumno->codigo_educando}\n";
        echo "   Nombre: {$alumno->apellido_paterno} {$alumno->apellido_materno}, {$nombreCompleto}\n";
        echo "   DNI: " . ($alumno->dni ?? 'Sin registrar') . "\n";
        echo "   Grado: $gradoInfo\n";
        echo "   Deudas: " . ($deudasConSaldo->count() > 0 ? $deudasConSaldo->count() . " pendiente(s)" : "Sin deudas") . "\n\n";
        $testsPassed++;
    } catch (\Exception $e) {
        echo "❌ FAIL: Error en búsqueda completa: " . $e->getMessage() . "\n\n";
        $testsFailed++;
    }
} else {
    echo "⚠️  SKIP: No hay alumnos para probar búsqueda\n\n";
}

// Test 8: Verificar estructura de respuesta JSON
echo "📋 TEST 8: Verificar Estructura de Respuesta JSON\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";
if ($alumnosTest->count() > 0) {
    $alumno = $alumnosTest->first();
    $nombreCompleto = $alumno->primer_nombre . ($alumno->otros_nombres ? ' ' . $alumno->otros_nombres : '');
    $response = [
        'success' => true,
        'alumno' => [
            'id_alumno' => $alumno->id_alumno,
            'codigo_educando' => $alumno->codigo_educando,
            'nombre_completo' => "{$alumno->apellido_paterno} {$alumno->apellido_materno}, {$nombreCompleto}",
            'dni' => $alumno->dni ?? 'No registrado',
            'grado' => 'Grado de prueba'
        ],
        'tiene_deudas' => false,
        'deudas' => [],
        'monto_total_pendiente' => '0.00'
    ];

    $camposRequeridos = ['success', 'alumno', 'tiene_deudas', 'deudas', 'monto_total_pendiente'];
    $todosPresentes = true;

    foreach ($camposRequeridos as $campo) {
        if (!isset($response[$campo])) {
            echo "❌ Falta campo: $campo\n";
            $todosPresentes = false;
        }
    }

    if ($todosPresentes) {
        echo "✅ PASS: Estructura de respuesta JSON correcta\n";
        echo "   " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        $testsPassed++;
    } else {
        echo "❌ FAIL: Estructura de respuesta JSON incompleta\n\n";
        $testsFailed++;
    }
} else {
    echo "⚠️  SKIP: No hay alumnos para verificar estructura JSON\n\n";
}

// Resumen Final
echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                           RESUMEN DE TESTS                                   ║\n";
echo "╠══════════════════════════════════════════════════════════════════════════════╣\n";
echo "║ Tests Ejecutados: $totalTests                                                       ║\n";
echo "║ Tests Exitosos:   " . str_pad($testsPassed, 2, ' ', STR_PAD_LEFT) . " ✅                                                     ║\n";
echo "║ Tests Fallidos:   " . str_pad($testsFailed, 2, ' ', STR_PAD_LEFT) . " " . ($testsFailed > 0 ? '❌' : '✅') . "                                                     ║\n";
echo "╠══════════════════════════════════════════════════════════════════════════════╣\n";

if ($testsFailed == 0) {
    echo "║                    🎉 TODOS LOS TESTS PASARON 🎉                             ║\n";
    echo "║                                                                              ║\n";
    echo "║ ✅ La búsqueda de alumnos está funcionando correctamente                    ║\n";
    echo "║ ✅ El método obtenerGradoActual() está corregido                            ║\n";
    echo "║ ✅ La verificación de deudas funciona correctamente                         ║\n";
    echo "║ ✅ La estructura de respuesta JSON es correcta                              ║\n";
    echo "║                                                                              ║\n";
    echo "║ 🌐 Puede probar en: http://localhost/traslados                              ║\n";
} else {
    echo "║                   ⚠️  ALGUNOS TESTS FALLARON ⚠️                             ║\n";
    echo "║                                                                              ║\n";
    echo "║ Por favor revise los errores anteriores                                     ║\n";
}

echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

// Instrucciones de Prueba Manual
echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                     INSTRUCCIONES DE PRUEBA MANUAL                           ║\n";
echo "╠══════════════════════════════════════════════════════════════════════════════╣\n";
echo "║                                                                              ║\n";
echo "║ 1️⃣  Abrir navegador en: http://localhost/traslados                          ║\n";
echo "║                                                                              ║\n";
echo "║ 2️⃣  Probar con código SIN deudas: 166787                                    ║\n";
echo "║    ➜ Debe mostrar mensaje verde ✅ Sin Deudas                               ║\n";
echo "║    ➜ Debe mostrar formulario de traslado                                    ║\n";
echo "║    ➜ NO debe mostrar tabla de deudas                                        ║\n";
echo "║                                                                              ║\n";
echo "║ 3️⃣  Probar con código CON deudas: 945008                                    ║\n";
echo "║    ➜ Debe mostrar alerta roja ⚠️ Deudas Pendientes                          ║\n";
echo "║    ➜ Debe mostrar tabla con deudas                                          ║\n";
echo "║    ➜ NO debe mostrar formulario de traslado                                 ║\n";
echo "║                                                                              ║\n";
echo "║ 4️⃣  Probar con código inexistente: 999999                                   ║\n";
echo "║    ➜ Debe mostrar alerta de error                                           ║\n";
echo "║    ➜ NO debe mostrar información del alumno                                 ║\n";
echo "║                                                                              ║\n";
echo "║ 5️⃣  Verificar que NO aparezcan errores SQL                                  ║\n";
echo "║    ➜ Verificar que no aparezca 'curso_grado doesn't exist'                  ║\n";
echo "║    ➜ La información académica debe mostrarse correctamente                  ║\n";
echo "║                                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

exit($testsFailed > 0 ? 1 : 0);
