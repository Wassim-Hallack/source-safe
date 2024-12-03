<?php

namespace App\Services\Export;

use App\Reports\ExportInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExport implements ExportInterface
{
    private string $blade_file_name;
    private string $exported_file_name;
    private array $data;

    public function __construct($blade_file_name, $exported_file_name, $data)
    {
        $this->blade_file_name = $blade_file_name;
        $this->exported_file_name = $exported_file_name;
        $this->data = $data;
    }

    public function export(): Response
    {
        $pdf = Pdf::loadView('exports.PDF.' . $this->blade_file_name, ['data' => $this->data]);

        return $pdf->download($this->exported_file_name . '.pdf');
    }
}
