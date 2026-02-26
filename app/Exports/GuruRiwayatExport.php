<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class GuruRiwayatExport implements FromArray
{
    /** @var array<int, array<int, string>> */
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }
}
