<?php

namespace App\Interfaces;

use App\Helpers\Exporter\ExportRequest;

interface IExportRequestFactory
{
    public function create(string $title, array $headers, array $data, ?array $options = null): ExportRequest;
}