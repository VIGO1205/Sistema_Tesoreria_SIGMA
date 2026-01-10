<?php

/**
 * Test Final: Crear Familiar como Director
 *
 * Este test verifica manualmente si el usuario director puede crear un familiar
 * vinculado a un usuario.
 */

echo "==========================================\n";
echo "TEST: INSTRUCCIONES PARA PRUEBA MANUAL\n";
echo "==========================================\n\n";

echo "✓ Servidor debe estar corriendo en http://127.0.0.1:8000\n\n";

// Verificar BD
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=bdsigma', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar usuario director
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = 'director' AND estado = 1");
    $stmt->execute();
    $director = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$director) {
        echo "❌ Usuario 'director' no encontrado o inactivo\n";
        exit(1);
    }

    echo "✓ Usuario director encontrado (ID: {$director['id_usuario']})\n";

    // Listar algunos usuarios disponibles
    $stmt = $pdo->query("SELECT id_usuario, username, tipo FROM users WHERE estado = 1 ORDER BY id_usuario LIMIT 5");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "✓ Usuarios disponibles para vincular:\n";
    foreach ($usuarios as $usuario) {
        echo "  - ID {$usuario['id_usuario']}: {$usuario['username']} ({$usuario['tipo']})\n";
    }

    echo "\n";

} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage() . "\n";
    exit(1);
}

echo "==========================================\n";
echo "PRUEBA MANUAL - SIGUE ESTOS PASOS:\n";
echo "==========================================\n\n";

echo "1. Abre tu navegador y ve a:\n";
echo "   http://127.0.0.1:8000/login\n\n";

echo "2. Inicia sesión con:\n";
echo "   Username: director\n";
echo "   Password: 12345\n\n";

echo "3. En el menú lateral, busca y haz clic en:\n";
echo "   Alumnos > Familiares\n\n";

echo "4. Haz clic en el botón 'Crear' o 'Nuevo'\n\n";

echo "5. VERIFICA QUE:\n";
echo "   ✓ Aparece un selector desplegable de 'Usuario' ANTES del campo DNI\n";
echo "   ✓ El selector tiene la etiqueta 'Usuario *' (con asterisco rojo)\n";
echo "   ✓ El selector muestra usuarios en formato: username - nombre (tipo)\n";
echo "   ✓ Hay una opción por defecto '-- Seleccione un usuario --'\n\n";

echo "6. Completa el formulario:\n";
echo "   - Selecciona un usuario del desplegable\n";
echo "   - DNI: 87654321\n";
echo "   - Apellido Paterno: TestDirector\n";
echo "   - Apellido Materno: Prueba\n";
echo "   - Primer Nombre: Familiar\n";
echo "   - Otros Nombres: Vinculado\n";
echo "   - Número de Contacto: 999888777\n";
echo "   - Correo: test@director.com\n\n";

echo "7. Haz clic en 'Crear'\n\n";

echo "8. VERIFICA QUE:\n";
echo "   ✓ El familiar se crea exitosamente\n";
echo "   ✓ Aparece un mensaje de confirmación\n";
echo "   ✓ Regresas a la lista de familiares\n\n";

echo "9. Para verificar en la BD, ejecuta:\n";
echo "   SELECT f.*, u.username, u.tipo \n";
echo "   FROM familiares f \n";
echo "   LEFT JOIN users u ON f.id_usuario = u.id_usuario \n";
echo "   WHERE f.dni = '87654321';\n\n";

echo "==========================================\n";
echo "VALIDACIONES ADICIONALES:\n";
echo "==========================================\n\n";

echo "PRUEBA 1: Intentar crear sin seleccionar usuario\n";
echo "  - NO selecciones ningún usuario\n";
echo "  - Llena los demás campos\n";
echo "  - Haz clic en 'Crear'\n";
echo "  - DEBE mostrar error: 'Debe seleccionar un usuario.'\n\n";

echo "PRUEBA 2: Verificar que la lista NO muestra la columna de usuario\n";
echo "  - Ve a la lista de familiares\n";
echo "  - Verifica que las columnas son: ID, DNI, Apellidos, Nombres, Contacto, Correo\n";
echo "  - NO debe aparecer una columna de 'Usuario'\n\n";

echo "==========================================\n";
echo "¿TODO CORRECTO? Entonces el CRUD está listo!\n";
echo "==========================================\n";
