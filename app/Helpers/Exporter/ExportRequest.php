<?php
namespace App\Helpers\Exporter;

use App\Interfaces\IExportRequest;

class ExportRequest implements IExportRequest
{
    private readonly string $title;
    private readonly array $headers;
    private readonly array $data;
    private readonly ?array $options;

    public function __construct(string $title, array $headers, array $data, ?array $options = null)
    {
        $this->title = $title;
        $this->headers = $headers;
        $this->data = $data;
        $this->options = $options;
    }

    public function title(): string
    {
        return $this->title;
    }
    public function headers(): array
    {
        return $this->headers;
    }
    public function data(): array
    {
        return $this->data;
    }
    public function options(): array
    {
        return $this->options ?? [];
    }
    public function option(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }
}