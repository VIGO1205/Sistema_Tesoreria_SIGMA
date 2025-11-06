<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFExportHelper
{
    /**
     * Exporta un PDF con Dompdf y configuración estándar SIGMA.
     */
    public static function exportPdf($fileName, $html, $options = [])
    {
        try {
            $dompdfOptions = new Options();
            
            // 🔥 CORRECCIÓN: Usar comillas simples consistentemente
            $dompdfOptions->set('defaultFont', $options['defaultFont'] ?? 'Arial');
            $dompdfOptions->set('isRemoteEnabled', true);
            $dompdfOptions->set('isHtml5ParserEnabled', true);
            $dompdfOptions->set('isPhpEnabled', true);
            
            // Configuraciones de debug
            $dompdfOptions->set('debugKeepTemp', false);
            $dompdfOptions->set('debugCss', false);
            $dompdfOptions->set('debugLayout', false);
            $dompdfOptions->set('debugLayoutLines', false);
            $dompdfOptions->set('debugLayoutBlocks', false);
            $dompdfOptions->set('debugLayoutInline', false);
            $dompdfOptions->set('debugLayoutPaddingBox', false);

            $dompdf = new Dompdf($dompdfOptions);
            $dompdf->loadHtml($html);
            $dompdf->setPaper($options['paper'] ?? 'A4', $options['orientation'] ?? 'portrait');
            $dompdf->render();

            $output = $dompdf->output();

            if (empty($output)) {
                throw new \Exception('El PDF generado está vacío');
            }

            \Log::info('PDF generado exitosamente, tamaño: ' . strlen($output) . ' bytes');

            return response($output, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Length' => strlen($output),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);

        } catch (\Exception $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Error generando PDF: ' . $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : 'Contacte al administrador'
            ], 500);
        }
    }

    /**
     * Genera HTML de tabla para PDF
     */
    public static function generateTableHtml(array $config): string
    {
        if (empty($config['rows']) || !is_array($config['rows'])) {
            $config['rows'] = [['Sin datos disponibles']];
            $config['headers'] = ['Información'];
        }

        $fecha = date('d/m/Y H:i:s');
        $totalRegistros = count($config['rows']);
        $title = $config['title'] ?? 'Reporte';
        $subtitle = $config['subtitle'] ?? '';
        $headers = $config['headers'] ?? [];
        $rows = $config['rows'] ?? [];
        $footer = $config['footer'] ?? '';

        $html = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header h2 {
            color: #4F46E5;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4F46E5;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>';

        $html .= '<div class="header">
            <h1>Sistema SIGMA</h1>';
        
        if ($subtitle) {
            $html .= '<h2>' . htmlspecialchars($subtitle) . '</h2>';
        }
        
        $html .= '<p>Reporte generado el ' . $fecha . '</p>
        </div>';

        $html .= '<div class="info-section">
            <p><strong>Total de registros:</strong> ' . $totalRegistros . '</p>
            <p><strong>Fecha de generación:</strong> ' . $fecha . '</p>
        </div>';

        $html .= '<table>
            <thead><tr>';
        
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        
        $html .= '</tr></thead>
            <tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>' . htmlspecialchars($cell ?? '') . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        if ($footer) {
            $html .= '<div class="footer">
                <p>' . htmlspecialchars($footer) . '</p>
            </div>';
        }

        $html .= '</body></html>';

        return $html;
    }
}
