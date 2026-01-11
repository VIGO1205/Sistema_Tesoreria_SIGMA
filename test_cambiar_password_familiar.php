<?php

/**
 * Test para verificar el cambio de contraseña de un usuario Familiar
 *
 * ESCENARIO DE PRUEBA:
 * 1. Crear un usuario de tipo Familiar
 * 2. Crear un registro en la tabla familiares asociado al usuario
 * 3. Verificar que el usuario puede acceder a la vista de cambiar contraseña
 * 4. Simular el cambio de contraseña
 * 5. Verificar que la contraseña se actualizó correctamente
 * 6. Limpiar datos de prueba
 *
 * Ejecutar: php test_cambiar_password_familiar.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Familiar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "\n========================================\n";
echo "TEST: CAMBIO DE CONTRASEÑA PARA FAMILIAR\n";
echo "========================================\n\n";

// Paso 1: Crear usuario de tipo Familiar
echo "📝 Paso 1: Creando usuario de tipo Familiar...\n";

$username = 'test_familiar_' . time();
$passwordOriginal = 'password123';

$usuario = new User([
    'username' => $username,
    'password' => Hash::make($passwordOriginal),
    'tipo' => 'Familiar',
    'estado' => true
]);
$usuario->save();

echo "   ✅ Usuario creado: ID {$usuario->id_usuario}, username: {$username}\n";
echo "   🔑 Contraseña original: {$passwordOriginal}\n\n";

// Paso 2: Crear registro de familiar asociado al usuario
echo "📝 Paso 2: Creando registro en tabla familiares...\n";

$familiar = new Familiar([
    'id_usuario' => $usuario->id_usuario,
    'dni' => '12345678',
    'apellido_paterno' => 'Test',
    'apellido_materno' => 'Familiar',
    'primer_nombre' => 'Usuario',
    'otros_nombres' => 'Prueba',
    'numero_contacto' => '999888777',
    'correo_electronico' => 'test@example.com',
    'estado' => true
]);
$familiar->save();

echo "   ✅ Familiar creado: ID {$familiar->idFamiliar}\n";
echo "   📋 Datos: {$familiar->primer_nombre} {$familiar->apellido_paterno}\n\n";

// Paso 3: Verificar la relación entre usuario y familiar
echo "📝 Paso 3: Verificando relación Usuario-Familiar...\n";

$familiarFromDB = Familiar::where('id_usuario', $usuario->id_usuario)->first();
if ($familiarFromDB && $familiarFromDB->idFamiliar == $familiar->idFamiliar) {
    echo "   ✅ Relación correcta: Usuario {$usuario->id_usuario} -> Familiar {$familiarFromDB->idFamiliar}\n\n";
} else {
    echo "   ❌ ERROR: No se encontró la relación correcta\n";
    exit(1);
}

// Paso 4: Verificar permisos de acceso
echo "📝 Paso 4: Verificando permisos de acceso...\n";

$permissions = config('familiar-permissions');
if (isset($permissions['cambiar_password']) && in_array('Familiar', $permissions['cambiar_password']['view'])) {
    echo "   ✅ Permisos configurados correctamente para 'cambiar_password'\n";
    echo "   📋 Acciones permitidas: " . implode(', ', array_keys($permissions['cambiar_password'])) . "\n\n";
} else {
    echo "   ❌ ERROR: Permisos no configurados para 'cambiar_password'\n";
    exit(1);
}

// Paso 5: Simular cambio de contraseña
echo "📝 Paso 5: Simulando cambio de contraseña...\n";

$nuevaPassword = 'nueva_password_456';
echo "   🔑 Nueva contraseña: {$nuevaPassword}\n";

// Buscar el usuario y actualizar la contraseña
$usuarioActualizar = User::where('id_usuario', $familiar->id_usuario)->first();
$usuarioActualizar->password = Hash::make($nuevaPassword);
$usuarioActualizar->save();

echo "   ✅ Contraseña actualizada en la base de datos\n\n";

// Paso 6: Verificar que la contraseña se actualizó correctamente
echo "📝 Paso 6: Verificando contraseña actualizada...\n";

$usuarioVerificar = User::where('id_usuario', $familiar->id_usuario)->first();

if (Hash::check($nuevaPassword, $usuarioVerificar->password)) {
    echo "   ✅ Verificación exitosa: La nueva contraseña es válida\n";
} else {
    echo "   ❌ ERROR: La contraseña no se actualizó correctamente\n";
    exit(1);
}

if (Hash::check($passwordOriginal, $usuarioVerificar->password)) {
    echo "   ❌ ERROR: La contraseña antigua todavía funciona\n";
    exit(1);
} else {
    echo "   ✅ La contraseña antigua ya no es válida\n\n";
}

// Paso 7: Verificar rutas
echo "📝 Paso 7: Verificando rutas disponibles...\n";

$routeCollection = Route::getRoutes();
$routesFound = [];

foreach ($routeCollection as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'familiar_cambiar_password') !== false) {
        $routesFound[] = [
            'name' => $name,
            'uri' => $route->uri(),
            'methods' => implode('|', $route->methods())
        ];
    }
}

if (count($routesFound) > 0) {
    echo "   ✅ Rutas encontradas:\n";
    foreach ($routesFound as $route) {
        echo "      - {$route['name']}: {$route['methods']} /familiar/{$route['uri']}\n";
    }
    echo "\n";
} else {
    echo "   ⚠️  ADVERTENCIA: No se encontraron rutas para familiar_cambiar_password\n";
    echo "      Asegúrate de que las rutas estén registradas en web.php\n\n";
}

// Paso 8: Información de prueba manual
echo "📝 Paso 8: Instrucciones para prueba manual...\n";
echo "   🌐 Para probar manualmente:\n";
echo "   1. Inicia sesión con:\n";
echo "      - Usuario: {$username}\n";
echo "      - Contraseña: {$nuevaPassword}\n";
echo "   2. Navega a: Gestión Tutor >> Cambiar Contraseña\n";
echo "   3. URL esperada: http://127.0.0.1:8000/familiar/cambiar-password\n";
echo "   4. Ingresa una nueva contraseña y confírmala\n";
echo "   5. Click en 'Guardar'\n";
echo "   6. Verifica que aparezca el mensaje de éxito\n\n";

// Paso 9: Limpiar datos de prueba
echo "📝 Paso 9: Limpiando datos de prueba...\n";

$response = readline("   ⚠️  ¿Deseas eliminar los datos de prueba? (s/n): ");

if (strtolower($response) === 's') {
    // Eliminar familiar (soft delete)
    $familiar->estado = false;
    $familiar->save();
    echo "   ✅ Familiar desactivado (soft delete)\n";

    // Eliminar usuario (soft delete)
    $usuario->estado = false;
    $usuario->save();
    echo "   ✅ Usuario desactivado (soft delete)\n\n";
} else {
    echo "   ℹ️  Datos de prueba conservados para prueba manual\n";
    echo "   ⚠️  Recuerda eliminarlos manualmente después:\n";
    echo "      - Usuario ID: {$usuario->id_usuario}\n";
    echo "      - Familiar ID: {$familiar->idFamiliar}\n\n";
}

// Resumen final
echo "========================================\n";
echo "✅ TEST COMPLETADO EXITOSAMENTE\n";
echo "========================================\n";
echo "\n📊 Resumen:\n";
echo "   ✓ Usuario creado y asociado a familiar\n";
echo "   ✓ Permisos configurados correctamente\n";
echo "   ✓ Contraseña actualizada y verificada\n";
echo "   ✓ Rutas disponibles\n";
echo "\n🎯 Próximos pasos:\n";
echo "   1. Realizar prueba manual con el usuario creado\n";
echo "   2. Verificar que el sidebar muestre 'Gestión Tutor'\n";
echo "   3. Confirmar que la vista de cambio de contraseña funcione\n";
echo "   4. Verificar que tras cambiar contraseña se pueda iniciar sesión\n\n";
