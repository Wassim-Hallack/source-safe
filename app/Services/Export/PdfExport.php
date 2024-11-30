<?php

namespace App\Services\Export;

use App\Reports\ExportInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExport implements ExportInterface
{
    public function export(array $data): Response
    {
        $pdf = Pdf::loadView('exports.PDF.file_operations', ['data' => $data]);

        return $pdf->download('file_operations_report.pdf');
    }
}
