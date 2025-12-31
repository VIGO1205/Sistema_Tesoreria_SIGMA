<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface IExporterService
{
    public function export(Request $request, IExportRequest $exportRequest);
    public function exportAsResponse(Request $request, IExportRequest $exportRequest);
}