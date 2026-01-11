<?php

/**
 * Test específico para verificar el usuario jeancito02
 * Ejecutar: php test_jeancito02.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Familiar;

echo "\n========================================\n";
echo "TEST: VERIFICACIÓN USUARIO JEANCITO02\n";
echo "========================================\n\n";

// Verificar usuario jeancito02
echo "📝 Buscando usuario 'jeancito02'...\n";

$user = User::where('username', 'jeancito02')->first();

if (!$user) {
    echo "   ❌ Usuario 'jeancito02' no encontrado\n";
    echo "   ℹ️  Creando usuario de prueba...\n\n";

    $user = new User([
        'username' => 'jeancito02',
        'password' => \Illuminate\Support\Facades\Hash::make('jeancito'),
        'tipo' => 'Familiar',
        'estado' => true
    ]);
    $user->save();

    echo "   ✅ Usuario creado: ID {$user->id_usuario}\n\n";

    // Crear familiar asociado
    $familiar = new Familiar([
        'id_usuario' => $user->id_usuario,
        'dni' => '87654321',
        'apellido_paterno' => 'Rodriguez',
        'apellido_materno' => 'Garcia',
        'primer_nombre' => 'Jean',
        'otros_nombres' => 'Carlos',
        'numero_contacto' => '999777888',
        'correo_electronico' => 'jeancito@example.com',
        'estado' => true
    ]);
    $familiar->save();

    echo "   ✅ Familiar creado: ID {$familiar->idFamiliar}\n\n";
} else {
    echo "   ✅ Usuario encontrado:\n";
    echo "      - ID: {$user->id_usuario}\n";
    echo "      - Username: {$user->username}\n";
    echo "      - Tipo: {$user->tipo}\n";
    echo "      - Estado: " . ($user->estado ? 'Activo' : 'Inactivo') . "\n\n";

    // Verificar familiar asociado
    echo "📝 Buscando familiar asociado...\n";
    $familiar = Familiar::where('id_usuario', $user->id_usuario)->first();

    if ($familiar) {
        echo "   ✅ Familiar encontrado:\n";
        echo "      - ID: {$familiar->idFamiliar}\n";
        echo "      - Nombre: {$familiar->primer_nombre} {$familiar->otros_nombres}\n";
        echo "      - Apellidos: {$familiar->apellido_paterno} {$familiar->apellido_materno}\n";
        echo "      - DNI: {$familiar->dni}\n";
        echo "      - Estado: " . ($familiar->estado ? 'Activo' : 'Inactivo') . "\n\n";
    } else {
        echo "   ⚠️  ADVERTENCIA: No se encontró registro en tabla familiares\n";
        echo "   ℹ️  Creando registro de familiar...\n\n";

        $familiar = new Familiar([
            'id_usuario' => $user->id_usuario,
            'dni' => '87654321',
            'apellido_paterno' => 'Rodriguez',
            'apellido_materno' => 'Garcia',
            'primer_nombre' => 'Jean',
            'otros_nombres' => 'Carlos',
            'numero_contacto' => '999777888',
            'correo_electronico' => 'jeancito@example.com',
            'estado' => true
        ]);
        $familiar->save();

        echo "   ✅ Familiar creado: ID {$familiar->idFamiliar}\n\n";
    }
}

// Verificar tipo de usuario
echo "📝 Verificando tipo de usuario...\n";
if ($user->tipo === 'Familiar') {
    echo "   ✅ Usuario es de tipo 'Familiar' - Puede acceder a cambiar contraseña\n\n";
} else {
    echo "   ⚠️  ADVERTENCIA: Usuario NO es de tipo 'Familiar' (actual: {$user->tipo})\n";
    echo "   ℹ️  Actualizando tipo a 'Familiar'...\n";
    $user->tipo = 'Familiar';
    $user->save();
    echo "   ✅ Tipo actualizado\n\n";
}

// Instrucciones finales
echo "========================================\n";
echo "✅ VERIFICACIÓN COMPLETADA\n";
echo "========================================\n\n";

echo "🎯 Instrucciones para prueba:\n";
echo "   1. Inicia el servidor: php artisan serve\n";
echo "   2. Abre el navegador: http://127.0.0.1:8000/login\n";
echo "   3. Inicia sesión con:\n";
echo "      - Usuario: jeancito02\n";
echo "      - Contraseña: jeancito\n";
echo "   4. En el sidebar izquierdo busca: 'Gestión Tutor'\n";
echo "   5. Click en: 'Cambiar Contraseña'\n";
echo "   6. Ingresa nueva contraseña (mínimo 6 caracteres)\n";
echo "   7. Confirma la contraseña\n";
echo "   8. Click en 'Guardar'\n";
echo "   9. Verifica el mensaje de éxito\n";
echo "   10. Cierra sesión e inicia con la nueva contraseña\n\n";

echo "🔗 URL directa: http://127.0.0.1:8000/familiar/cambiar-password\n\n";
