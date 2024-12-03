<?php

namespace App\Services\Export;

use App\Reports\ExportInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CsvExport implements ExportInterface
{
    private string $file_name;
    protected array $data;

    public function __construct($file_name, array $data)
    {
        $this->data = $data;
        $this->file_name = $file_name;
    }

    public function export(): BinaryFileResponse
    {
        $fileName = $this->file_name . 'csv';

        $file = fopen(storage_path('app/' . $fileName), 'w');
        foreach ($this->data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return response()->download(storage_path('app/' . $fileName))->deleteFileAfterSend(true);
    }
}
