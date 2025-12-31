<?php

namespace App\Interfaces;

interface IExporterFactory
{
    public function getExporter(string $type): ITableExporter;
}