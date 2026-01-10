<?php

/**
 * Test: Crear Familiar Vinculado a Usuario
 *
 * Este script prueba el flujo completo para crear un familiar vinculado a un usuario
 * desde la vista de director.
 *
 * Usuario: director / 12345
 *
 * Flujo:
 * 1. Login como director
 * 2. Navegar a la página de crear familiar
 * 3. Verificar que aparece el selector de usuarios
 * 4. Crear un familiar vinculado a un usuario
 * 5. Verificar que el familiar se creó correctamente con id_usuario
 */

echo "==========================================\n";
echo "TEST: CREAR FAMILIAR VINCULADO A USUARIO\n";
echo "==========================================\n\n";

// URL base
$baseUrl = 'http://127.0.0.1:8000';

// Iniciar sesión
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

$response = curl_exec($ch);

// Extraer CSRF token
preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', $response, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "❌ Error: No se pudo obtener el CSRF token\n";
    exit(1);
}

echo "✓ CSRF Token obtenido\n";

// Hacer login
curl_setopt($ch, CURLOPT_URL, "$baseUrl/login");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $csrfToken,
    'username' => 'director',
    'password' => '12345'
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode != 200) {
    echo "❌ Error en login: HTTP $httpCode\n";
    exit(1);
}

echo "✓ Login exitoso como 'director'\n";

// Obtener la página de crear familiar
curl_setopt($ch, CURLOPT_URL, "$baseUrl/alumnos/familiares/crear");
curl_setopt($ch, CURLOPT_POST, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode != 200) {
    echo "❌ Error al acceder a crear familiar: HTTP $httpCode\n";
    exit(1);
}

echo "✓ Página de crear familiar accedida\n";

// Verificar que existe el selector de usuarios
if (strpos($response, 'name="id_usuario"') !== false) {
    echo "✓ Selector de usuario encontrado en el formulario\n";
} else {
    echo "❌ Error: No se encontró el selector de usuario\n";
    exit(1);
}

// Contar usuarios disponibles
preg_match_all('/<option value="(\d+)">[^<]+<\/option>/', $response, $usuarioMatches);
$usuariosDisponibles = count($usuarioMatches[1]);
echo "✓ Usuarios disponibles para seleccionar: $usuariosDisponibles\n";

if ($usuariosDisponibles == 0) {
    echo "❌ Error: No hay usuarios disponibles\n";
    exit(1);
}

// Obtener el primer usuario disponible
$primerUsuarioId = $usuarioMatches[1][0];
echo "✓ Usando usuario ID: $primerUsuarioId\n";

// Extraer nuevo CSRF token
preg_match('/<input type="hidden" name="_token" value="([^"]+)"/', $response, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "❌ Error: No se pudo obtener el nuevo CSRF token\n";
    exit(1);
}

echo "✓ Nuevo CSRF Token obtenido\n";

// Crear un familiar de prueba
$timestamp = time();
$familiarData = [
    '_token' => $csrfToken,
    '_method' => 'PUT',
    'id_usuario' => $primerUsuarioId,
    'dni' => '12345678' . substr($timestamp, -2),
    'apellido_paterno' => 'Prueba',
    'apellido_materno' => 'Test',
    'primer_nombre' => 'Familiar',
    'otros_nombres' => 'Vinculado',
    'numero_contacto' => '999888777',
    'correo_electronico' => "familiar_test_{$timestamp}@test.com"
];

echo "\n--- Datos del familiar a crear ---\n";
echo "Usuario ID: " . $familiarData['id_usuario'] . "\n";
echo "DNI: " . $familiarData['dni'] . "\n";
echo "Nombres: " . $familiarData['primer_nombre'] . " " . $familiarData['otros_nombres'] . "\n";
echo "Apellidos: " . $familiarData['apellido_paterno'] . " " . $familiarData['apellido_materno'] . "\n";
echo "Email: " . $familiarData['correo_electronico'] . "\n";
echo "-----------------------------------\n\n";

curl_setopt($ch, CURLOPT_URL, "$baseUrl/alumnos/familiares/crear");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($familiarData));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode != 200) {
    echo "❌ Error al crear familiar: HTTP $httpCode\n";

    // Buscar errores de validación
    if (strpos($response, 'error') !== false || strpos($response, 'Error') !== false) {
        echo "\n--- Posibles errores encontrados en la respuesta ---\n";
        preg_match_all('/<span class="text-sm text-red-500">([^<]+)<\/span>/', $response, $errorMatches);
        if (!empty($errorMatches[1])) {
            foreach ($errorMatches[1] as $error) {
                echo "  • $error\n";
            }
        }
        echo "---------------------------------------------------\n";
    }
    exit(1);
}

echo "✓ Familiar creado exitosamente\n";

// Verificar que se muestra el mensaje de éxito
if (strpos($response, 'created') !== false || strpos($response, 'exitosamente') !== false || strpos($response, 'éxito') !== false) {
    echo "✓ Mensaje de confirmación encontrado\n";
}

// Conectar a la base de datos para verificar
echo "\n--- Verificando en la base de datos ---\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=sigma_tesoreria', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM familiares WHERE dni = ? ORDER BY idFamiliar DESC LIMIT 1");
    $stmt->execute([$familiarData['dni']]);
    $familiar = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($familiar) {
        echo "✓ Familiar encontrado en la base de datos\n";
        echo "  ID: " . $familiar['idFamiliar'] . "\n";
        echo "  Usuario ID: " . ($familiar['id_usuario'] ?: 'NULL') . "\n";
        echo "  DNI: " . $familiar['dni'] . "\n";
        echo "  Nombre completo: " . $familiar['primer_nombre'] . " " . $familiar['otros_nombres'] . " " . $familiar['apellido_paterno'] . " " . $familiar['apellido_materno'] . "\n";

        if ($familiar['id_usuario'] == $primerUsuarioId) {
            echo "✓ El familiar está correctamente vinculado al usuario ID: $primerUsuarioId\n";

            // Verificar que el usuario existe
            $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id_usuario = ?");
            $stmtUser->execute([$familiar['id_usuario']]);
            $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo "✓ Usuario vinculado encontrado:\n";
                echo "  Username: " . $user['username'] . "\n";
                echo "  Nombre: " . $user['name'] . "\n";
                echo "  Tipo: " . $user['tipo'] . "\n";
            }

        } else {
            echo "❌ Error: El familiar NO está vinculado al usuario correcto\n";
            echo "  Esperado: $primerUsuarioId\n";
            echo "  Actual: " . ($familiar['id_usuario'] ?: 'NULL') . "\n";
            exit(1);
        }
    } else {
        echo "❌ Error: Familiar no encontrado en la base de datos\n";
        exit(1);
    }

} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
    exit(1);
}

curl_close($ch);

echo "\n==========================================\n";
echo "✓ TODAS LAS PRUEBAS PASARON EXITOSAMENTE\n";
echo "==========================================\n";
