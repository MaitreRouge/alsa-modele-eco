<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DevisExport implements WithMultipleSheets
{
    use Exportable;

    protected $uid;

    public function __construct(int $uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        for ($typeId = 1; $typeId <= 3; $typeId++) {
            $sheets[] = new PrestationSheet($this->uid, $typeId);
        }

        return $sheets;
    }
}
