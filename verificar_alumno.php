<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE CÓDIGO EDUCANDO ===\n\n";

$codigo = '478114';
echo "Buscando código: {$codigo}\n\n";

// Buscar exacto
$alumno = App\Models\Alumno::where('codigo_educando', $codigo)->first();

if ($alumno) {
    echo "✅ ENCONTRADO:\n";
    echo "   ID: {$alumno->id_alumno}\n";
    echo "   Código: [{$alumno->codigo_educando}]\n";
    echo "   Nombre: {$alumno->primer_nombre} {$alumno->apellido_paterno}\n\n";
} else {
    echo "❌ NO ENCONTRADO\n\n";
    
    // Mostrar primeros 10 códigos
    echo "Primeros 10 códigos en la tabla alumnos:\n";
    $alumnos = App\Models\Alumno::take(10)->get(['id_alumno', 'codigo_educando', 'primer_nombre', 'apellido_paterno']);
    
    foreach ($alumnos as $a) {
        $len = strlen($a->codigo_educando);
        echo "   - ID: {$a->id_alumno} | Código: [{$a->codigo_educando}] (longitud: {$len}) | {$a->primer_nombre} {$a->apellido_paterno}\n";
    }
    
    // Buscar con LIKE
    echo "\n\nBuscando con LIKE '%478114%':\n";
    $alumnoLike = App\Models\Alumno::where('codigo_educando', 'LIKE', "%{$codigo}%")->first();
    if ($alumnoLike) {
        echo "✅ ENCONTRADO CON LIKE:\n";
        echo "   Código: [{$alumnoLike->codigo_educando}]\n";
        echo "   Nombre: {$alumnoLike->primer_nombre} {$alumnoLike->apellido_paterno}\n";
    } else {
        echo "❌ Tampoco encontrado con LIKE\n";
    }
}
