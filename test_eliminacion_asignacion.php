<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "PRUEBA DE ELIMINACIÓN DE ASIGNACIÓN\n";
echo "========================================\n\n";

try {
    // 1. Crear una asignación de prueba
    echo "1. Creando asignación de prueba...\n";

    $alumno = DB::table('alumnos')->where('estado', 1)->first();
    $familiar = DB::table('familiares')->where('estado', 1)->first();

    if (!$alumno || !$familiar) {
        echo "   ✗ No hay datos para prueba\n";
        exit(1);
    }

    $nombreAlumno = trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno . ' ' . $alumno->primer_nombre);
    $nombreFamiliar = trim($familiar->apellido_paterno . ' ' . $familiar->apellido_materno . ' ' . $familiar->primer_nombre);

    // Limpiar si existe
    DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->delete();

    // Crear nueva
    DB::table('composiciones_familiares')->insert([
        'id_alumno' => $alumno->id_alumno,
        'id_familiar' => $familiar->idFamiliar,
        'parentesco' => 'Padre',
        'estado' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    echo "   ✓ Asignación creada:\n";
    echo "     - Alumno: {$nombreAlumno} (ID: {$alumno->id_alumno})\n";
    echo "     - Familiar: {$nombreFamiliar} (ID: {$familiar->idFamiliar})\n\n";

    // 2. Verificar que existe
    echo "2. Verificando que existe en BD...\n";
    $existe = DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->exists();

    if (!$existe) {
        echo "   ✗ Error: No se encontró el registro\n";
        exit(1);
    }
    echo "   ✓ Registro existe en BD\n\n";

    // 3. Simular el request del modal (como viene del formulario)
    echo "3. Simulando request de eliminación del modal...\n";

    // El modal envía el ID compuesto en el campo 'id'
    $comboId = $alumno->id_alumno . '|' . $familiar->idFamiliar;
    echo "   ID compuesto: {$comboId}\n";

    // Separar los IDs (como lo hace el controller)
    $ids = explode('|', $comboId);

    if (count($ids) !== 2) {
        echo "   ✗ Error: ID inválido\n";
        exit(1);
    }
    echo "   ✓ ID válido y separado correctamente\n\n";

    // 4. Validar que sean numéricos
    echo "4. Validando IDs numéricos...\n";
    $idAlumno = filter_var($ids[0], FILTER_VALIDATE_INT);
    $idFamiliar = filter_var($ids[1], FILTER_VALIDATE_INT);

    if ($idAlumno === false || $idFamiliar === false) {
        echo "   ✗ Error: IDs no son numéricos\n";
        exit(1);
    }
    echo "   ✓ ID Alumno: {$idAlumno}\n";
    echo "   ✓ ID Familiar: {$idFamiliar}\n\n";

    // 5. Buscar el registro (como lo hace el controller)
    echo "5. Buscando registro a eliminar...\n";
    $composicion = DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->first();

    if (!$composicion) {
        echo "   ✗ Error: Registro no encontrado\n";
        exit(1);
    }
    echo "   ✓ Registro encontrado\n\n";

    // 6. Eliminar permanentemente
    echo "6. Eliminando permanentemente de la BD...\n";
    $deleted = DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->delete();

    if ($deleted === 0) {
        echo "   ✗ Error: No se eliminó ningún registro\n";
        exit(1);
    }
    echo "   ✓ Registro eliminado ({$deleted} fila afectada)\n\n";

    // 7. Verificar que ya no existe
    echo "7. Verificando eliminación permanente...\n";
    $existe = DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->exists();

    if ($existe) {
        echo "   ✗ Error: El registro todavía existe\n";
        exit(1);
    }
    echo "   ✓ Confirmado: El registro fue eliminado permanentemente\n\n";

    echo "========================================\n";
    echo "✓ PRUEBA COMPLETADA EXITOSAMENTE\n";
    echo "========================================\n\n";

    echo "RESUMEN:\n";
    echo "✓ ID compuesto formateado correctamente (alumno|familiar)\n";
    echo "✓ IDs separados y validados\n";
    echo "✓ Registro encontrado en BD\n";
    echo "✓ Eliminación permanente ejecutada (DELETE)\n";
    echo "✓ Registro eliminado de la BD\n\n";

    echo "CAMBIO APLICADO EN EL CONTROLLER:\n";
    echo "❌ ANTES: ->dataInputName('id_alumno')\n";
    echo "✅ AHORA: ->dataInputName('id')\n\n";

    echo "Esto asegura que el modal envíe el campo 'id' que el controller\n";
    echo "espera recibir con el ID compuesto (alumno|familiar).\n\n";

} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Traza:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
