<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    private $apiKey;
    private $model;

    public function __construct()
    {
        // API Key de Groq - agrégala al .env
        $this->apiKey = env('GROQ_API_KEY', '');
        $this->model = 'llama-3.3-70b-versatile'; // Modelo más reciente de Groq
    }

    /**
     * Analizar un voucher de pago usando el texto extraído
     * 
     * @param string $textoVoucher Texto extraído del voucher por OCR
     * @param float $montoEsperado Monto que se espera en el pago
     * @param string $fechaEsperada Fecha esperada del pago
     * @return array ['porcentaje' => int, 'recomendacion' => string, 'razon' => string]
     */
    public function analizarVoucher($textoVoucher, $montoEsperado, $fechaEsperada)
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'porcentaje' => 50,
                    'recomendacion' => 'pendiente',
                    'razon' => 'API Key de Groq no configurada'
                ];
            }

            // Crear el prompt para Groq
            $prompt = $this->crearPromptAnalisis($textoVoucher, $montoEsperado, $fechaEsperada);

            // Llamar a la API de Groq
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un experto en validación de comprobantes de pago y transacciones bancarias. Tu tarea es analizar vouchers y determinar su autenticidad y validez.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 500,
            ]);

            if (!$response->successful()) {
                Log::error('Error en Groq API: ' . $response->body());
                
                return [
                    'porcentaje' => 50,
                    'recomendacion' => 'pendiente',
                    'razon' => 'Error al conectar con Groq'
                ];
            }

            $data = $response->json();
            $respuesta = $data['choices'][0]['message']['content'] ?? '';

            // Parsear la respuesta de Groq
            return $this->parsearRespuesta($respuesta);

        } catch (\Exception $e) {
            Log::error('Error en GroqService: ' . $e->getMessage());
            
            return [
                'porcentaje' => 50,
                'recomendacion' => 'pendiente',
                'razon' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Crear el prompt para el análisis
     */
    private function crearPromptAnalisis($textoVoucher, $montoEsperado, $fechaEsperada)
    {
        return <<<PROMPT
Analiza el siguiente texto extraído de un voucher de pago y determina si es válido:

TEXTO DEL VOUCHER:
{$textoVoucher}

DATOS ESPERADOS:
- Monto esperado: S/ {$montoEsperado}
- Fecha esperada: {$fechaEsperada}

INSTRUCCIONES:
1. Verifica si el monto coincide o es aproximado
2. Verifica si la fecha es correcta o cercana
3. Busca números de operación o referencia
4. Detecta si parece un voucher legítimo o falso

RESPONDE EN EL SIGUIENTE FORMATO EXACTO (sin texto adicional):
PORCENTAJE: [número entre 0 y 100]
RECOMENDACION: [validado o rechazado]
RAZON: [explicación breve de máximo 100 caracteres]

Ejemplo:
PORCENTAJE: 85
RECOMENDACION: validado
RAZON: Monto y fecha coinciden, voucher tiene número de operación válido
PROMPT;
    }

    /**
     * Parsear la respuesta de Groq
     */
    private function parsearRespuesta($respuesta)
    {
        // Valores por defecto
        $porcentaje = 50;
        $recomendacion = 'pendiente';
        $razon = 'No se pudo analizar';

        // Extraer PORCENTAJE
        if (preg_match('/PORCENTAJE:\s*(\d+)/i', $respuesta, $matches)) {
            $porcentaje = intval($matches[1]);
            $porcentaje = max(0, min(100, $porcentaje)); // Limitar entre 0-100
        }

        // Extraer RECOMENDACION
        if (preg_match('/RECOMENDACION:\s*(validado|rechazado)/i', $respuesta, $matches)) {
            $recomendacion = strtolower($matches[1]);
        }

        // Extraer RAZON
        if (preg_match('/RAZON:\s*(.+?)(?:\n|$)/i', $respuesta, $matches)) {
            $razon = trim($matches[1]);
        }

        return [
            'porcentaje' => $porcentaje,
            'recomendacion' => $recomendacion,
            'razon' => $razon
        ];
    }
}
