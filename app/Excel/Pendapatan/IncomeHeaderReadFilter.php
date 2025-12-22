<?php

namespace App\Excel\Pendapatan;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class IncomeHeaderReadFilter implements IReadFilter
{
    protected int $headerRow = 6;

    public function readCell($column, $row, $worksheetName = '')
    {
        if (stripos($worksheetName, 'income') === false) {
            return false;
        }

        return $row === $this->headerRow;
    }
}
