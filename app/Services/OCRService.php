<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OCRService
{
    private $apiKey;

    public function __construct()
    {
        // Usaremos OCR.space (gratuito, 25,000 requests/mes)
        // API Key: puedes obtenerla gratis en https://ocr.space/ocrapi
        $this->apiKey = env('OCR_API_KEY', 'K87899142388957'); // API key de prueba
    }

    /**
     * Extraer texto de una imagen o PDF
     * 
     * @param string $filePath Ruta del archivo (puede ser imagen o PDF)
     * @return array ['texto' => string, 'success' => bool, 'error' => string|null]
     */
    public function extraerTexto($filePath)
    {
        try {
            // Verificar que el archivo existe
            if (!file_exists($filePath)) {
                return [
                    'success' => false,
                    'texto' => '',
                    'error' => 'El archivo no existe'
                ];
            }

            // Preparar la petición a OCR.space
            $response = Http::asMultipart()
                ->post('https://api.ocr.space/parse/image', [
                    [
                        'name' => 'file',
                        'contents' => file_get_contents($filePath),
                        'filename' => basename($filePath)
                    ],
                    [
                        'name' => 'apikey',
                        'contents' => $this->apiKey
                    ],
                    [
                        'name' => 'language',
                        'contents' => 'spa'
                    ],
                    [
                        'name' => 'isOverlayRequired',
                        'contents' => 'false'
                    ],
                    [
                        'name' => 'detectOrientation',
                        'contents' => 'true'
                    ],
                    [
                        'name' => 'scale',
                        'contents' => 'true'
                    ],
                    [
                        'name' => 'OCREngine',
                        'contents' => '2'
                    ]
                ]);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'texto' => '',
                    'error' => 'Error al conectar con el servicio OCR'
                ];
            }

            $data = $response->json();

            // Verificar si hubo error
            if (isset($data['IsErroredOnProcessing']) && $data['IsErroredOnProcessing']) {
                return [
                    'success' => false,
                    'texto' => '',
                    'error' => $data['ErrorMessage'][0] ?? 'Error desconocido en OCR'
                ];
            }

            // Extraer el texto
            $texto = '';
            if (isset($data['ParsedResults']) && count($data['ParsedResults']) > 0) {
                $texto = $data['ParsedResults'][0]['ParsedText'] ?? '';
            }

            return [
                'success' => true,
                'texto' => trim($texto),
                'error' => null
            ];

        } catch (\Exception $e) {
            Log::error('Error en OCRService: ' . $e->getMessage());
            
            return [
                'success' => false,
                'texto' => '',
                'error' => 'Error al procesar el archivo: ' . $e->getMessage()
            ];
        }
    }
}
