<?php

namespace App\Excel\Pesanan;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class IncomeHeaderReadFilter implements IReadFilter
{
    protected int $headerRow = 1;

    public function readCell($column, $row, $worksheetName = '')
    {
        if (stripos($worksheetName, 'orders') === false) {
            return false;
        }

        return $row === $this->headerRow;
    }
}
