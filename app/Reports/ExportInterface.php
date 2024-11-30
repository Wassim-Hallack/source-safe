<?php

namespace App\Reports;

interface ExportInterface
{
    public function export(array $data);
}
