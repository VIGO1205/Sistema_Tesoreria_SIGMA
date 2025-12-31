<?php
namespace App\Helpers\Exporter;

use App\Interfaces\ITableExporter;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use \Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ExcelAdapter implements FromView
{
    private ExportRequest $request;

    public function __construct(ExportRequest $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        return view('export.pdf', [
            'title' => $this->request->title(),
            'date' => Carbon::now()->format('d/m/Y H:i:s'),
            'data' => $this->request->data(),
            'headers' => $this->request->headers(),
        ]);
    }
}

class ExcelExporter implements ITableExporter
{
    public function __construct()
    {

    }

    public function export(ExportRequest $request)
    {
        $adapter = new ExcelAdapter($request);
        return Excel::download($adapter, 'table.xlsx');
    }

    public function exportAsResponse(ExportRequest $request)
    {
        return $this->export($request);
    }
}