<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapPeriodeSheetExport implements FromArray, WithTitle
{
    /** @var array<int, array<int, string>> */
    protected array $rows;

    protected string $title;

    public function __construct(array $rows, string $title)
    {
        $this->rows = $rows;
        $this->title = $title;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return mb_substr($this->title, 0, 31);
    }
}
