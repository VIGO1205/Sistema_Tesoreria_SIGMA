<?php

namespace App\Helpers\Exporter\Services;

use App\Helpers\Exporter\Enum\Exporter;
use App\Interfaces\IExporterFactory;
use App\Interfaces\IExporterService;
use App\Interfaces\IExportRequest;
use Illuminate\Http\Request;

class ExporterService implements IExporterService
{
    private IExporterFactory $exporterFactory;

    public function __construct(IExporterFactory $exporterFactory)
    {
        $this->exporterFactory = $exporterFactory;
    }

    public function getExporter(Request $request)
    {
        $filetype = $request->input('export');

        try {
            return $this->exporterFactory->getExporter($filetype);
        } catch (\Exception $e) {
            abort(400, 'Formato de exportación inválido');
        }
    }

    public function export(Request $request, IExportRequest $exportRequest)
    {
        $exporter = $this->getExporter($request);

        return $exporter->export($exportRequest);
    }

    public function exportAsResponse(Request $request, IExportRequest $exportRequest)
    {
        $exporter = $this->getExporter($request);

        return $exporter->exportAsResponse($exportRequest);
    }
}