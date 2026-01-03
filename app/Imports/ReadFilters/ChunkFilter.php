<?php

namespace App\Imports\ReadFilters;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkFilter implements IReadFilter
{
    private $startRow = 0;
    private $endRow = 0;
    public function setRows($startRow, $chunkSize) {
        $this->startRow = $startRow;
        $this->endRow   = $startRow + $chunkSize;
    }
    public function readCell($columnAddress, $row, $worksheetName = '') {
        // Kita butuh baris 6 (header) dan range data saat ini
        if ($row == 6 || ($row >= $this->startRow && $row < $this->endRow)) return true;
        return false;
    }
}
