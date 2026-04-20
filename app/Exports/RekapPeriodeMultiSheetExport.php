<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapPeriodeMultiSheetExport implements WithMultipleSheets
{
    /** @var array<int, array{title: string, rows: array<int, array<int, string>>}> */
    protected array $sheetsData;

    /**
     * @param array<int, array{title: string, rows: array<int, array<int, string>>}> $sheetsData
     */
    public function __construct(array $sheetsData)
    {
        $this->sheetsData = $sheetsData;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->sheetsData as $sheetData) {
            $sheets[] = new \App\Exports\RekapPeriodeSheetExport($sheetData['rows'], $sheetData['title']);
        }

        return $sheets;
    }
}
