<?php
namespace App\Helpers\Exporter\Factories;

use App\Helpers\Exporter\Enum\Exporter;
use App\Helpers\Exporter\SnappyPDFExporter;
use App\Interfaces\IExporterFactory;
use App\Interfaces\ITableExporter;
use App\Helpers\Exporter\PDFExporter;
use App\Helpers\Exporter\ExcelExporter;

class ExporterFactory implements IExporterFactory
{
    public const VIEW_TEMPLATE = "export.pdf";

    private ITableExporter $PDF_EXPORTER;
    private ITableExporter $EXCEL_EXPORTER;
    private ITableExporter $PDF_DOMPDF_EXPORTER;

    public function __construct()
    {
        $this->PDF_EXPORTER = new SnappyPDFExporter(self::VIEW_TEMPLATE);
        $this->EXCEL_EXPORTER = new ExcelExporter(self::VIEW_TEMPLATE);
        $this->PDF_DOMPDF_EXPORTER = new PDFExporter(self::VIEW_TEMPLATE);
    }

    public function getExporter(string $type): ITableExporter
    {
        switch ($type) {
            case Exporter::PDF:
                return $this->PDF_EXPORTER;
            case Exporter::EXCEL:
                return $this->EXCEL_EXPORTER;
            case Exporter::PDF_DOMPDF:
                return $this->PDF_DOMPDF_EXPORTER;
            default:
                throw new \Exception("Exportador no implementado");
        }
    }
}