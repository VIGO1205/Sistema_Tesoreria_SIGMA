<?php

namespace App\Interfaces;

interface IExportRequest
{
    public function title(): string;
    public function headers(): array;
    public function data(): array;
    public function options(): array;
    public function option(string $key, $default = null);
}
