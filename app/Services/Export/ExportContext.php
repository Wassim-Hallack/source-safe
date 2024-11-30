<?php

namespace App\Services\Export;

use App\Reports\ExportInterface;

class ExportContext
{
    protected ExportInterface $exportStrategy;

    public function __construct(ExportInterface $exportStrategy)
    {
        $this->exportStrategy = $exportStrategy;
    }

    public function export(array $data)
    {
        return $this->exportStrategy->export($data);
    }
}
