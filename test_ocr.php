<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\OCRService;
use App\Services\GroqService;

echo "=== Test de OCR y Groq ===\n\n";

// 1. Probar OCR
echo "1. Probando OCR Service...\n";
$ocrService = new OCRService();

// Buscar un voucher de prueba
$voucherPath = 'storage/app/public/vouchers'; // Ajusta según tu estructura
if (is_dir($voucherPath)) {
    $files = glob($voucherPath . '/*.*');
    if (!empty($files)) {
        $testFile = $files[0];
        echo "Archivo de prueba: $testFile\n";
        
        $resultado = $ocrService->extraerTexto($testFile);
        
        if ($resultado['success']) {
            echo "✅ OCR exitoso!\n";
            echo "Texto extraído: " . substr($resultado['texto'], 0, 200) . "...\n\n";
            
            // 2. Probar Groq
            echo "2. Probando Groq Service...\n";
            $groqService = new GroqService();
            $analisis = $groqService->analizarVoucher($resultado['texto'], 100.00, '01/11/2025');
            
            echo "✅ Análisis de Groq:\n";
            echo "Porcentaje: " . $analisis['porcentaje'] . "%\n";
            echo "Recomendación: " . $analisis['recomendacion'] . "\n";
            echo "Razón: " . $analisis['razon'] . "\n";
        } else {
            echo "❌ Error en OCR: " . $resultado['error'] . "\n";
        }
    } else {
        echo "❌ No se encontraron archivos en $voucherPath\n";
    }
} else {
    echo "❌ No existe el directorio $voucherPath\n";
    echo "Por favor, verifica la ruta de los vouchers.\n";
}
