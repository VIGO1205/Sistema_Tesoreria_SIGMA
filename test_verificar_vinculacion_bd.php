<?php

/**
 * Test Simplificado: Verificar que el formulario tiene el selector de usuario
 */

echo "==========================================\n";
echo "TEST: VERIFICAR SELECTOR DE USUARIO\n";
echo "==========================================\n\n";

// Conectar a la base de datos
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=bdsigma', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "1. Verificando usuarios disponibles en la BD:\n";
    $stmt = $pdo->query("SELECT id_usuario, username, tipo, estado FROM users WHERE estado = 1 LIMIT 10");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($usuarios)) {
        echo "❌ No hay usuarios activos en la base de datos\n";
        exit(1);
    }

    echo "✓ Usuarios activos encontrados: " . count($usuarios) . "\n";
    foreach ($usuarios as $usuario) {
        echo "  - ID: {$usuario['id_usuario']}, Username: {$usuario['username']}, Tipo: {$usuario['tipo']}\n";
    }

    echo "\n2. Verificando estructura de la tabla familiares:\n";
    $stmt = $pdo->query("DESCRIBE familiares");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $hasIdUsuario = false;
    foreach ($columns as $column) {
        if ($column['Field'] == 'id_usuario') {
            $hasIdUsuario = true;
            echo "✓ Columna 'id_usuario' existe en la tabla familiares\n";
            echo "  Tipo: {$column['Type']}\n";
            echo "  Null: {$column['Null']}\n";
            echo "  Key: {$column['Key']}\n";
            break;
        }
    }

    if (!$hasIdUsuario) {
        echo "❌ La columna 'id_usuario' NO existe en la tabla familiares\n";
        exit(1);
    }

    echo "\n3. Probando crear un familiar vinculado:\n";

    $primerUsuario = $usuarios[0];
    $timestamp = time();

    $stmt = $pdo->prepare("INSERT INTO familiares (id_usuario, dni, apellido_paterno, apellido_materno, primer_nombre, otros_nombres, numero_contacto, correo_electronico, estado, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())");

    $testData = [
        $primerUsuario['id_usuario'],
        '99988877' . substr($timestamp, -2),
        'ApellidoP',
        'ApellidoM',
        'Nombre',
        'Test',
        '999888777',
        "test_{$timestamp}@test.com"
    ];

    $stmt->execute($testData);
    $familiarId = $pdo->lastInsertId();

    echo "✓ Familiar creado con ID: $familiarId\n";

    // Verificar que se creó correctamente
    $stmt = $pdo->prepare("SELECT * FROM familiares WHERE idFamiliar = ?");
    $stmt->execute([$familiarId]);
    $familiar = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($familiar && $familiar['id_usuario'] == $primerUsuario['id_usuario']) {
        echo "✓ Familiar vinculado correctamente al usuario ID: {$familiar['id_usuario']}\n";
        echo "  DNI: {$familiar['dni']}\n";
        echo "  Nombre: {$familiar['primer_nombre']} {$familiar['apellido_paterno']}\n";

        // Verificar relación con usuario
        $stmt = $pdo->prepare("SELECT u.* FROM users u WHERE u.id_usuario = ?");
        $stmt->execute([$familiar['id_usuario']]);
        $usuarioVinculado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuarioVinculado) {
            echo "✓ Usuario vinculado encontrado:\n";
            echo "  Username: {$usuarioVinculado['username']}\n";
            echo "  Tipo: {$usuarioVinculado['tipo']}\n";
        }
    } else {
        echo "❌ Error: El familiar no se vinculó correctamente\n";
        exit(1);
    }

    // Limpiar
    $stmt = $pdo->prepare("DELETE FROM familiares WHERE idFamiliar = ?");
    $stmt->execute([$familiarId]);
    echo "\n✓ Registro de prueba eliminado\n";

} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n==========================================\n";
echo "✓ VERIFICACIÓN DE BD EXITOSA\n";
echo "==========================================\n";
echo "\nAhora verifica manualmente en el navegador:\n";
echo "1. Ve a: http://127.0.0.1:8000/login\n";
echo "2. Inicia sesión con: director / 12345\n";
echo "3. Ve a: Alumnos > Familiares > Crear\n";
echo "4. Verifica que aparece un selector de usuarios\n";
echo "5. Crea un familiar seleccionando un usuario\n";
