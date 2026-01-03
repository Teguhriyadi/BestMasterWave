<?php

namespace App\Imports\ReadFilters;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class HeadersFilter implements IReadFilter
{
    public function readCell($columnAddress, $row, $worksheetName = '') {
        if ($row <= 10) {
            return true;
        }
        return false;
    }
}
