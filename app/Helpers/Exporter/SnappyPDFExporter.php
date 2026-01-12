<?php

namespace App\Helpers\Exporter;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;

class SnappyPDFExporter extends PDFExporter
{
    public function __construct(string $template, ?array $options = null)
    {
        parent::__construct($template, $options);
    }

    protected function getDefaultOptions(): array
    {
        $options = [
            'encoding' => 'UTF-8',
            'enable-local-file-access' => true,
        ];

        return $options;
    }

    public function export(ExportRequest $request)
    {
        $pdf = SnappyPdf::loadView($this->template, [
            'title' => $request->title(),
            'date' => Carbon::now()->format('d/m/Y H:i:s'),
            'data' => $request->data(),
            'headers' => $request->headers(),
        ]);

        $pdf->setOptions($this->options ?? $this->getDefaultOptions());
        $pdf->setPaper('A4', 'portrait');

        // dd($pdf);

        return $pdf;
    }
}