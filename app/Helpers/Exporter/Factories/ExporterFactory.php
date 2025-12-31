<?php
namespace App\Helpers\Exporter\Factories;

use App\Helpers\Exporter\Enum\Exporter;
use App\Interfaces\IExporterFactory;
use App\Interfaces\ITableExporter;
use App\Helpers\Exporter\PDFExporter;
use App\Helpers\Exporter\ExcelExporter;

class ExporterFactory implements IExporterFactory
{
    public const PDF_VIEW_TEMPLATE = "export.pdf";

    private ITableExporter $PDF_EXPORTER;
    private ITableExporter $EXCEL_EXPORTER;

    public function __construct()
    {
        $this->PDF_EXPORTER = new PDFExporter(self::PDF_VIEW_TEMPLATE);
        $this->EXCEL_EXPORTER = new ExcelExporter();
    }

    public function getExporter(string $type): ITableExporter
    {
        switch ($type) {
            case Exporter::PDF:
                return $this->PDF_EXPORTER;
            case Exporter::EXCEL:
                return $this->EXCEL_EXPORTER;
            default:
                throw new \Exception("Exportador no implementado");
        }
    }
}