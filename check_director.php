<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Buscando usuario 'director'...\n\n";

$director = App\Models\User::where('username', 'director')->first();

if ($director) {
    echo "✓ Usuario director encontrado:\n";
    echo "  ID: {$director->id_usuario}\n";
    echo "  Username: {$director->username}\n";
    echo "  Tipo: {$director->tipo}\n";
    echo "  Estado: " . ($director->estado ? 'Activo' : 'Inactivo') . "\n";
    echo "  Password hash: " . substr($director->password, 0, 20) . "...\n";
} else {
    echo "❌ Usuario 'director' NO encontrado\n";
    echo "\nCreando usuario director...\n";

    $director = App\Models\User::create([
        'username' => 'director',
        'tipo' => 'Administrativo',
        'password' => bcrypt('12345'),
        'estado' => 1,
    ]);

    echo "✓ Usuario director creado con ID: {$director->id_usuario}\n";
}
