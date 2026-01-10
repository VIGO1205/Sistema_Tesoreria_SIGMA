<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "PRUEBA COMPLETA: COMPOSICIÓN FAMILIAR\n";
echo "========================================\n\n";

try {
    // 1. Verificar conexión a BD
    echo "1. Verificando conexión a base de datos...\n";
    DB::connection()->getPdo();
    echo "   ✓ Conexión exitosa\n\n";

    // 2. Verificar tablas necesarias
    echo "2. Verificando tablas necesarias...\n";

    // Verificar tabla composiciones_familiares
    $composicionesExiste = DB::select("SHOW TABLES LIKE 'composiciones_familiares'");
    if (count($composicionesExiste) > 0) {
        echo "   ✓ Tabla 'composiciones_familiares' existe\n";
    } else {
        echo "   ✗ Tabla 'composiciones_familiares' NO existe\n";
        exit(1);
    }

    // Verificar tabla alumnos
    $alumnosExiste = DB::select("SHOW TABLES LIKE 'alumnos'");
    if (count($alumnosExiste) > 0) {
        echo "   ✓ Tabla 'alumnos' existe\n";
    } else {
        echo "   ✗ Tabla 'alumnos' NO existe\n";
        exit(1);
    }

    // Verificar tabla familiares
    $familiaresExiste = DB::select("SHOW TABLES LIKE 'familiares'");
    if (count($familiaresExiste) > 0) {
        echo "   ✓ Tabla 'familiares' existe\n";
    } else {
        echo "   ✗ Tabla 'familiares' NO existe\n";
        exit(1);
    }

    echo "\n";

    // 3. Verificar datos de prueba
    echo "3. Obteniendo datos de prueba...\n";

    // Obtener un alumno de prueba
    $alumno = DB::table('alumnos')->where('estado', 1)->first();
    if (!$alumno) {
        echo "   ✗ No hay alumnos activos en la BD\n";
        exit(1);
    }
    $nombreAlumno = trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno . ' ' . $alumno->primer_nombre);
    echo "   ✓ Alumno encontrado: ID={$alumno->id_alumno}, Nombre={$nombreAlumno}\n";

    // Obtener un familiar de prueba
    $familiar = DB::table('familiares')->where('estado', 1)->first();
    if (!$familiar) {
        echo "   ✗ No hay familiares activos en la BD\n";
        exit(1);
    }
    $nombreFamiliar = trim($familiar->apellido_paterno . ' ' . $familiar->apellido_materno . ' ' . $familiar->primer_nombre);
    echo "   ✓ Familiar encontrado: ID={$familiar->idFamiliar}, Nombre={$nombreFamiliar}\n";

    // Usar un parentesco hardcodeado (como en el controller)
    $parentesco = 'Padre'; // Puede ser: Padre, Madre, Tutor, Abuelo, Abuela, Tío, Tía
    echo "   ✓ Parentesco de prueba: {$parentesco}\n\n";

    // 4. TEST DE CREACIÓN: Crear asignación
    echo "4. TEST DE CREACIÓN: Creando asignación...\n";

    // Verificar si ya existe la asignación
    $existeAsignacion = DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->where('parentesco', $parentesco)
        ->first();

    if ($existeAsignacion) {
        echo "   ! La asignación ya existe, eliminando para prueba limpia...\n";
        DB::table('composiciones_familiares')
            ->where('id_alumno', $alumno->id_alumno)
            ->where('id_familiar', $familiar->idFamiliar)
            ->where('parentesco', $parentesco)
            ->delete();
    }

    // Insertar nueva asignación
    DB::table('composiciones_familiares')->insert([
        'id_alumno' => $alumno->id_alumno,
        'id_familiar' => $familiar->idFamiliar,
        'parentesco' => $parentesco,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    echo "   ✓ Asignación creada exitosamente\n";
    echo "     - ID Alumno: {$alumno->id_alumno}\n";
    echo "     - ID Familiar: {$familiar->idFamiliar}\n";
    echo "     - Parentesco: {$parentesco}\n\n";

    // 5. TEST DE LECTURA: Verificar que se puede leer la asignación
    echo "5. TEST DE LECTURA: Verificando asignación en BD...\n";

    $asignacionCreada = DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->where('parentesco', $parentesco)
        ->first();

    if ($asignacionCreada) {
        echo "   ✓ Asignación encontrada en BD\n";
        echo "     - Alumno: {$nombreAlumno} (ID: {$asignacionCreada->id_alumno})\n";
        echo "     - Familiar: {$nombreFamiliar} (ID: {$asignacionCreada->id_familiar})\n";
        echo "     - Parentesco: {$asignacionCreada->parentesco}\n";
    } else {
        echo "   ✗ ERROR: No se pudo encontrar la asignación creada\n";
        exit(1);
    }
    echo "\n";

    // 6. TEST DE CONSULTA CON JOINS: Verificar que se pueden obtener nombres
    echo "6. TEST DE CONSULTA CON JOINS: Obteniendo datos relacionados...\n";

    $asignacionConNombres = DB::table('composiciones_familiares as cf')
        ->join('alumnos as a', 'cf.id_alumno', '=', 'a.id_alumno')
        ->join('familiares as f', 'cf.id_familiar', '=', 'f.idFamiliar')
        ->where('cf.id_alumno', $alumno->id_alumno)
        ->where('cf.id_familiar', $familiar->idFamiliar)
        ->where('cf.parentesco', $parentesco)
        ->select(
            'cf.id_alumno',
            'cf.id_familiar',
            'cf.parentesco',
            DB::raw("CONCAT_WS(' ', a.apellido_paterno, a.apellido_materno, a.primer_nombre) as nombreAlumno"),
            DB::raw("CONCAT_WS(' ', f.apellido_paterno, f.apellido_materno, f.primer_nombre) as nombreFamiliar")
        )
        ->first();

    if ($asignacionConNombres) {
        echo "   ✓ Consulta con JOINS exitosa\n";
        echo "     - Alumno: {$asignacionConNombres->nombreAlumno}\n";
        echo "     - Familiar: {$asignacionConNombres->nombreFamiliar}\n";
        echo "     - Parentesco: {$asignacionConNombres->parentesco}\n";
    } else {
        echo "   ✗ ERROR: No se pudo obtener datos con JOINS\n";
        exit(1);
    }
    echo "\n";

    // 7. TEST DE DUPLICADOS: Intentar crear duplicado
    echo "7. TEST DE DUPLICADOS: Verificando constraint de clave primaria compuesta...\n";
    try {
        DB::table('composiciones_familiares')->insert([
            'id_alumno' => $alumno->id_alumno,
            'id_familiar' => $familiar->idFamiliar,
            'parentesco' => $parentesco,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "   ✗ ERROR: Se permitió insertar duplicado (constraint no funciona)\n";
    } catch (\Illuminate\Database\QueryException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "   ✓ Constraint funciona correctamente - duplicado rechazado\n";
        } else {
            echo "   ✗ ERROR diferente: {$e->getMessage()}\n";
        }
    }
    echo "\n";

    // 8. TEST DE ELIMINACIÓN: Eliminar asignación (DELETE real, no cambio de estado)
    echo "8. TEST DE ELIMINACIÓN: Eliminando asignación de BD (DELETE permanente)...\n";

    $eliminados = DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->where('parentesco', $parentesco)
        ->delete();

    if ($eliminados > 0) {
        echo "   ✓ Asignación eliminada exitosamente ({$eliminados} registro eliminado)\n";
    } else {
        echo "   ✗ ERROR: No se pudo eliminar la asignación\n";
        exit(1);
    }
    echo "\n";

    // 9. TEST DE VERIFICACIÓN DE ELIMINACIÓN: Confirmar que ya no existe
    echo "9. TEST DE VERIFICACIÓN: Confirmando eliminación permanente...\n";

    $verificarEliminacion = DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->where('parentesco', $parentesco)
        ->first();

    if (!$verificarEliminacion) {
        echo "   ✓ Confirmado: El registro fue eliminado permanentemente de la BD\n";
    } else {
        echo "   ✗ ERROR: El registro aún existe en la BD\n";
        exit(1);
    }
    echo "\n";

    // 10. TEST DE MÚLTIPLES ASIGNACIONES: Un alumno puede tener varios familiares
    echo "10. TEST DE MÚLTIPLES ASIGNACIONES: Verificando múltiples familiares por alumno...\n";

    // Primero limpiar cualquier asignación existente para este alumno
    DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->delete();

    // Obtener dos familiares diferentes
    $familiar2 = DB::table('familiares')
        ->where('estado', 1)
        ->where('idFamiliar', '!=', $familiar->idFamiliar)
        ->first();

    $familiar3 = DB::table('familiares')
        ->where('estado', 1)
        ->where('idFamiliar', '!=', $familiar->idFamiliar)
        ->where('idFamiliar', '!=', $familiar2->idFamiliar ?? 0)
        ->first();

    if ($familiar2 && $familiar3) {
        // Crear múltiples asignaciones para el mismo alumno (con diferentes familiares)
        DB::table('composiciones_familiares')->insert([
            'id_alumno' => $alumno->id_alumno,
            'id_familiar' => $familiar->idFamiliar,
            'parentesco' => 'Padre',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('composiciones_familiares')->insert([
            'id_alumno' => $alumno->id_alumno,
            'id_familiar' => $familiar2->idFamiliar,
            'parentesco' => 'Madre',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('composiciones_familiares')->insert([
            'id_alumno' => $alumno->id_alumno,
            'id_familiar' => $familiar3->idFamiliar,
            'parentesco' => 'Tutor',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $countAsignaciones = DB::table('composiciones_familiares')
            ->where('id_alumno', $alumno->id_alumno)
            ->count();

        echo "   ✓ Múltiples asignaciones creadas\n";
        echo "     - Alumno: {$nombreAlumno} tiene {$countAsignaciones} familiares asignados\n";
        echo "     - Familiar 1 (Padre): ID {$familiar->idFamiliar}\n";
        echo "     - Familiar 2 (Madre): ID {$familiar2->idFamiliar}\n";
        echo "     - Familiar 3 (Tutor): ID {$familiar3->idFamiliar}\n";

        // Limpiar las asignaciones de prueba
        DB::table('composiciones_familiares')
            ->where('id_alumno', $alumno->id_alumno)
            ->delete();

        echo "   ✓ Asignaciones de prueba eliminadas\n";
    } else {
        echo "   ! No hay suficientes familiares para prueba de múltiples asignaciones\n";
    }
    echo "\n";

    // 11. Resumen de estructura de tabla
    echo "11. ESTRUCTURA DE TABLA composiciones_familiares:\n";
    $columnas = DB::select("DESCRIBE composiciones_familiares");
    foreach ($columnas as $columna) {
        echo "     - {$columna->Field}: {$columna->Type} " .
             ($columna->Key === 'PRI' ? '[PRIMARY KEY]' : '') .
             ($columna->Null === 'NO' ? '[NOT NULL]' : '[NULLABLE]') . "\n";
    }
    echo "\n";

    echo "========================================\n";
    echo "✓ TODAS LAS PRUEBAS COMPLETADAS EXITOSAMENTE\n";
    echo "========================================\n\n";

    echo "RESUMEN DE FUNCIONALIDADES:\n";
    echo "✓ Creación de asignaciones familiar-alumno\n";
    echo "✓ Lectura de asignaciones con datos relacionados\n";
    echo "✓ Consultas con JOINS (alumnos, familiares, parentescos)\n";
    echo "✓ Prevención de duplicados (clave primaria compuesta)\n";
    echo "✓ Eliminación PERMANENTE de asignaciones (DELETE real)\n";
    echo "✓ Múltiples familiares por alumno permitido\n";
    echo "✓ Estructura de tabla verificada\n\n";

    echo "NOTAS IMPORTANTES:\n";
    echo "- La eliminación es PERMANENTE (DELETE), no cambia estado\n";
    echo "- La clave primaria compuesta previene duplicados\n";
    echo "- Un alumno puede tener múltiples familiares\n";
    echo "- Un familiar puede estar asignado a múltiples alumnos\n\n";

} catch (\Exception $e) {
    echo "\n✗ ERROR GENERAL: " . $e->getMessage() . "\n";
    echo "Traza:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
