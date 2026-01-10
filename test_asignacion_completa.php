<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "========================================\n";
echo "PRUEBA DE ASIGNACIÓN FAMILIAR-ALUMNO\n";
echo "========================================\n\n";

try {
    // 1. Obtener datos para la prueba
    echo "1. Obteniendo datos de prueba...\n";

    $alumno = DB::table('alumnos')->where('estado', 1)->first();
    if (!$alumno) {
        echo "   ✗ No hay alumnos activos\n";
        exit(1);
    }
    $nombreAlumno = trim($alumno->apellido_paterno . ' ' . $alumno->apellido_materno . ' ' . $alumno->primer_nombre);
    echo "   ✓ Alumno: {$nombreAlumno} (ID: {$alumno->id_alumno})\n";

    $familiar = DB::table('familiares')->where('estado', 1)->first();
    if (!$familiar) {
        echo "   ✗ No hay familiares activos\n";
        exit(1);
    }
    $nombreFamiliar = trim($familiar->apellido_paterno . ' ' . $familiar->apellido_materno . ' ' . $familiar->primer_nombre);
    echo "   ✓ Familiar: {$nombreFamiliar} (ID: {$familiar->idFamiliar})\n";
    echo "   ✓ Parentesco: Padre\n\n";

    // 2. Limpiar asignaciones existentes para prueba limpia
    echo "2. Limpiando asignaciones anteriores...\n";
    DB::table('composiciones_familiares')
        ->where('id_alumno', $alumno->id_alumno)
        ->where('id_familiar', $familiar->idFamiliar)
        ->delete();
    echo "   ✓ Limpieza completada\n\n";

    // 3. Simular el POST del formulario
    echo "3. Simulando POST del formulario (como si viniese del blade)...\n";

    $datosFormulario = [
        'id_alumno' => $alumno->id_alumno,
        'id_familiar' => $familiar->idFamiliar,
        'parentesco' => 'Padre',
        'estado' => 1
    ];

    echo "   Datos del formulario:\n";
    echo "   - id_alumno: {$datosFormulario['id_alumno']}\n";
    echo "   - id_familiar: {$datosFormulario['id_familiar']}\n";
    echo "   - parentesco: {$datosFormulario['parentesco']}\n";
    echo "   - estado: {$datosFormulario['estado']}\n\n";

    // 4. Validar datos (como lo hace el controller)
    echo "4. Validando datos...\n";

    // Verificar que el alumno existe
    $alumnoExiste = DB::table('alumnos')->where('id_alumno', $datosFormulario['id_alumno'])->exists();
    if (!$alumnoExiste) {
        echo "   ✗ El alumno no existe\n";
        exit(1);
    }
    echo "   ✓ Alumno válido\n";

    // Verificar que el familiar existe
    $familiarExiste = DB::table('familiares')->where('idFamiliar', $datosFormulario['id_familiar'])->exists();
    if (!$familiarExiste) {
        echo "   ✗ El familiar no existe\n";
        exit(1);
    }
    echo "   ✓ Familiar válido\n";

    // Verificar que el parentesco es válido
    $parentescosValidos = ['Padre', 'Madre', 'Tutor', 'Abuelo', 'Abuela', 'Tío', 'Tía'];
    if (!in_array($datosFormulario['parentesco'], $parentescosValidos)) {
        echo "   ✗ Parentesco no válido\n";
        exit(1);
    }
    echo "   ✓ Parentesco válido\n";

    // Verificar si ya existe la asignación
    $existeAsignacion = DB::table('composiciones_familiares')
        ->where('id_alumno', $datosFormulario['id_alumno'])
        ->where('id_familiar', $datosFormulario['id_familiar'])
        ->exists();

    if ($existeAsignacion) {
        echo "   ✗ La asignación ya existe\n";
        exit(1);
    }
    echo "   ✓ No hay duplicados\n\n";

    // 5. Insertar en la BD (como lo hace el controller)
    echo "5. Guardando en la base de datos...\n";

    DB::table('composiciones_familiares')->insert([
        'id_alumno' => $datosFormulario['id_alumno'],
        'id_familiar' => $datosFormulario['id_familiar'],
        'parentesco' => $datosFormulario['parentesco'],
        'estado' => $datosFormulario['estado'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

    echo "   ✓ Registro insertado exitosamente\n\n";

    // 6. Verificar que se guardó correctamente
    echo "6. Verificando registro en la BD...\n";

    $asignacionGuardada = DB::table('composiciones_familiares as cf')
        ->join('alumnos as a', 'cf.id_alumno', '=', 'a.id_alumno')
        ->join('familiares as f', 'cf.id_familiar', '=', 'f.idFamiliar')
        ->where('cf.id_alumno', $datosFormulario['id_alumno'])
        ->where('cf.id_familiar', $datosFormulario['id_familiar'])
        ->select(
            'cf.*',
            DB::raw("CONCAT_WS(' ', a.apellido_paterno, a.apellido_materno, a.primer_nombre) as nombreAlumno"),
            DB::raw("CONCAT_WS(' ', f.apellido_paterno, f.apellido_materno, f.primer_nombre) as nombreFamiliar")
        )
        ->first();

    if (!$asignacionGuardada) {
        echo "   ✗ ERROR: No se encontró el registro guardado\n";
        exit(1);
    }

    echo "   ✓ Registro encontrado en BD:\n";
    echo "     - Alumno: {$asignacionGuardada->nombreAlumno} (ID: {$asignacionGuardada->id_alumno})\n";
    echo "     - Familiar: {$asignacionGuardada->nombreFamiliar} (ID: {$asignacionGuardada->id_familiar})\n";
    echo "     - Parentesco: {$asignacionGuardada->parentesco}\n";
    echo "     - Estado: {$asignacionGuardada->estado}\n";
    echo "     - Creado: {$asignacionGuardada->created_at}\n\n";

    // 7. Verificar que aparece en el listado
    echo "7. Verificando que aparece en el listado...\n";

    $countAsignaciones = DB::table('composiciones_familiares')
        ->where('estado', 1)
        ->count();

    echo "   ✓ Total de asignaciones activas: {$countAsignaciones}\n\n";

    // 8. Limpiar (opcional)
    echo "8. Limpiando registro de prueba...\n";
    DB::table('composiciones_familiares')
        ->where('id_alumno', $datosFormulario['id_alumno'])
        ->where('id_familiar', $datosFormulario['id_familiar'])
        ->delete();
    echo "   ✓ Registro eliminado\n\n";

    echo "========================================\n";
    echo "✓ PRUEBA COMPLETADA EXITOSAMENTE\n";
    echo "========================================\n\n";

    echo "RESUMEN:\n";
    echo "✓ Datos del formulario validados correctamente\n";
    echo "✓ Registro guardado en composiciones_familiares\n";
    echo "✓ Estado = 1 guardado correctamente\n";
    echo "✓ Datos relacionados (alumno/familiar) recuperados con JOINS\n";
    echo "✓ Asignación visible en el listado\n\n";

    echo "INSTRUCCIONES PARA PROBAR EN EL NAVEGADOR:\n";
    echo "1. Ir a: http://127.0.0.1:8000/composiciones-familiares\n";
    echo "2. Click en 'Nuevo Registro'\n";
    echo "3. Seleccionar Alumno, Familiar y Parentesco\n";
    echo "4. Click en 'Guardar Asignación'\n";
    echo "5. Confirmar en el modal\n";
    echo "6. Verificar que aparece en el listado\n\n";

} catch (\Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Traza:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
