<?php

namespace App\Excel\Pendapatan;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class IncomeDataReadFilter implements IReadFilter
{
    public int $startRow = 7;

    public function readCell($column, $row, $worksheetName = '')
    {
        if (stripos($worksheetName, 'income') === false) {
            return false;
        }

        return $row >= $this->startRow;
    }
}
