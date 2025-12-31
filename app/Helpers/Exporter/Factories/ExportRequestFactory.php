<?php

namespace App\Helpers\Exporter\Factories;

use App\Helpers\Exporter\ExportRequest;
use App\Interfaces\IExportRequestFactory;

class ExportRequestFactory implements IExportRequestFactory
{
    public function create(string $title, array $headers, array $data, ?array $options = null): ExportRequest
    {
        return new ExportRequest($title, $headers, $data, $options);
    }
}
