<?php

namespace App\Excel\Pesanan;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class IncomeDataReadFilter implements IReadFilter
{
    public int $startRow = 2;

    public function readCell($column, $row, $worksheetName = '')
    {
        if (stripos($worksheetName, 'orders') === false) {
            return false;
        }

        return $row >= $this->startRow;
    }
}
