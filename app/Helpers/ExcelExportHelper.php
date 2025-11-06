<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelExportHelper
{
    /**
     * Exporta un archivo Excel con formato estándar SIGMA.
     * @param string $fileName Nombre del archivo a descargar
     * @param array $headers Encabezados de la tabla
     * @param iterable $rows Datos a exportar
     * @param callable $rowCallback function($sheet, $rowIndex, $item): void
     * @param string $title Título del documento
     * @param string $subject Asunto del documento
     * @param string $description Descripción del documento
     * @return \Illuminate\Http\Response
     */
    public static function exportExcel($fileName, array $headers, iterable $rows, callable $rowCallback, $title = 'Exportación SIGMA', $subject = '', $description = '')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('Sistema SIGMA')
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription($description);

        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $column++;
        }

        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        $lastCol = chr(ord('A') + count($headers) - 1);
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);

        $rowIndex = 2;
        foreach ($rows as $item) {
            $rowCallback($sheet, $rowIndex, $item);
            $rowIndex++;
        }

        foreach (range('A', $lastCol) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:' . $lastCol . ($rowIndex - 1))->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);
        $responseHeaders = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
            'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Pragma' => 'public',
        ];
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, $responseHeaders);
    }
}
