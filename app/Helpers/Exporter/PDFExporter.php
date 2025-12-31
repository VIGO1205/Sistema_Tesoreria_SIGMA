<?php
namespace App\Helpers\Exporter;

use App\Interfaces\ITableExporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\View\View;

class PDFExporter implements ITableExporter
{
    private string $template;
    private ?array $options;

    public function __construct(string $template, ?array $options = null)
    {
        $this->template = $template;
        $this->options = $options;
    }

    private function getDefaultOptions(): array
    {
        $options = [
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
        ];

        return $options;
    }

    private function getDebugOptions(): array
    {
        $options = $this->getDefaultOptions();
        $options['debugKeepTemp'] = true;
        $options['debugCss'] = true;
        $options['debugLayout'] = true;
        $options['debugLayoutLines'] = true;
        $options['debugLayoutBlocks'] = true;
        $options['debugLayoutInline'] = true;
        $options['debugLayoutPaddingBox'] = true;

        return $options;
    }

    public function export(ExportRequest $request): \Barryvdh\DomPDF\PDF
    {
        $pdf = Pdf::loadView($this->template, [
            'title' => $request->title(),
            'date' => Carbon::now()->format('d/m/Y H:i:s'),
            'data' => $request->data(),
            'headers' => $request->headers(),
        ]);

        $pdf->setOptions($this->options ?? $this->getDefaultOptions());
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    public function exportAsResponse(ExportRequest $request): \Illuminate\Http\Response
    {
        $pdf = $this->export($request);
        return $pdf->stream($request->option('filename', 'document') . '.pdf');
    }
}