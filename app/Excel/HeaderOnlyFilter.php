<?php

namespace App\Excel;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class HeaderOnlyFilter implements IReadFilter
{
    public function readCell($column, $row, $worksheetName = '')
    {
        return $row === 1;
    }
}
