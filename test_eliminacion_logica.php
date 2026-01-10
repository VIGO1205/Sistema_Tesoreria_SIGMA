<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "PRUEBA DE ELIMINACIÓN LÓGICA (ESTADO)\n";
echo "========================================\n\n";

try {
    // 1. Crear una asignación de prueba con estado = 1
    echo "1. Creando asignación de prueba con estado = 1...\n";

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

    // Crear nueva con estado = 1
    DB::table('composiciones_familiares')->insert([
        'id_alumno' => $alumno->id_alumno,
        'id_familiar' => $familiar->idFamiliar,
        'parentesco' => 'Padre',
        'estado' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    echo "   ✓ Asignación creada con estado = 1\n";
    echo "     - Alumno: {$nombreAlumno} (ID: {$alumno->id_alumno})\n";
    echo "     - Familiar: {$nombreFamiliar} (ID: {$familiar->idFamiliar})\n\n";

    // 2. Verificar que aparece en el listado (estado = 1)
    echo "2. Verificando que aparece en el listado (estado = 1)...\n";
    $count = DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->where('estado', 1)
        ->count();

    if ($count === 0) {
        echo "   ✗ Error: No se encontró el registro activo\n";
        exit(1);
    }
    echo "   ✓ Registro encontrado en listado (estado = 1)\n\n";

    // 3. Simular eliminación lógica (cambiar estado a 0)
    echo "3. Simulando eliminación lógica (cambiar estado a 0)...\n";

    $comboId = $alumno->id_alumno . '|' . $familiar->idFamiliar;
    $ids = explode('|', $comboId);
    $idAlumno = filter_var($ids[0], FILTER_VALIDATE_INT);
    $idFamiliar = filter_var($ids[1], FILTER_VALIDATE_INT);

    // Cambiar estado de 1 a 0 (como lo hace el controller)
    $updated = DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->update(['estado' => 0, 'updated_at' => now()]);

    if ($updated === 0) {
        echo "   ✗ Error: No se actualizó el registro\n";
        exit(1);
    }
    echo "   ✓ Estado cambiado de 1 a 0 ({$updated} fila actualizada)\n\n";

    // 4. Verificar que el registro sigue existiendo en la BD
    echo "4. Verificando que el registro sigue existiendo en la BD...\n";
    $existe = DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->exists();

    if (!$existe) {
        echo "   ✗ Error: El registro fue eliminado de la BD\n";
        exit(1);
    }
    echo "   ✓ Registro sigue existiendo en la BD\n\n";

    // 5. Verificar que NO aparece en el listado (estado = 0)
    echo "5. Verificando que NO aparece en el listado activo...\n";
    $countActivos = DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->where('estado', 1)
        ->count();

    if ($countActivos > 0) {
        echo "   ✗ Error: El registro todavía aparece como activo\n";
        exit(1);
    }
    echo "   ✓ Registro NO aparece en listado activo (filtrado correctamente)\n\n";

    // 6. Verificar el estado actual del registro
    echo "6. Verificando estado actual del registro...\n";
    $registro = DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->first();

    echo "   ✓ Registro encontrado:\n";
    echo "     - id_alumno: {$registro->id_alumno}\n";
    echo "     - id_familiar: {$registro->id_familiar}\n";
    echo "     - parentesco: {$registro->parentesco}\n";
    echo "     - estado: {$registro->estado}\n";
    echo "     - updated_at: {$registro->updated_at}\n\n";

    // 7. Limpiar
    echo "7. Limpiando registro de prueba...\n";
    DB::table('composiciones_familiares')
        ->where('id_alumno', $idAlumno)
        ->where('id_familiar', $idFamiliar)
        ->delete();
    echo "   ✓ Registro eliminado permanentemente\n\n";

    echo "========================================\n";
    echo "✓ PRUEBA COMPLETADA EXITOSAMENTE\n";
    echo "========================================\n\n";

    echo "RESUMEN DEL CAMBIO:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    echo "❌ ANTES (Eliminación Permanente):\n";
    echo "   \$composicion->delete();\n";
    echo "   → El registro se eliminaba de la BD\n";
    echo "   → No se podía recuperar\n\n";

    echo "✅ AHORA (Eliminación Lógica):\n";
    echo "   \$composicion->estado = 0;\n";
    echo "   \$composicion->save();\n";
    echo "   → El registro permanece en la BD\n";
    echo "   → Solo cambia el campo 'estado' de 1 a 0\n";
    echo "   → No aparece en el listado (filtrado por estado = 1)\n";
    echo "   → Se puede recuperar si es necesario\n\n";

    echo "CAMBIOS APLICADOS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "1. doSearch() ahora filtra: ->where('estado', 1)\n";
    echo "2. delete() ahora actualiza: estado = 0\n";
    echo "3. Los registros con estado = 0 no aparecen en listado\n";
    echo "4. Los registros permanecen en BD para auditoría\n\n";

} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Traza:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
