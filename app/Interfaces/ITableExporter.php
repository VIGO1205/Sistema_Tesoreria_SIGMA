<?php

namespace App\Interfaces;

use App\Helpers\Exporter\ExportRequest;

interface ITableExporter
{
    public function export(ExportRequest $request);

    public function exportAsResponse(ExportRequest $request);
}