<?php

namespace App\Exports;

use App\Models\Categorie;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use MongoDB\Driver\Query;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PrestationSheet implements FromView, WithTitle, WithColumnFormatting, ShouldAutoSize
{
    private $uid;
    private $typeId;

    public function __construct(int $uid, int $typeId)
    {
        $this->uid = $uid;
        $this->typeId  = $typeId;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return Categorie::find($this->typeId)->label;
    }

    public function view(): View
    {
        $prestations = DB::select("
        SELECT DISTINCT d.*
        FROM devis AS d, prestations as p, categories as c
        WHERE ((d.catalogueID = p.id
            AND d.version = p.version
            AND p.idCategorie = c.id
            AND c.parentID IN (SELECT c.id FROM categories as c WHERE c.parentID = :cID)
            )
            OR d.parent = :parent)
            AND d.clientID = :clientID
        ", ["clientID" => $this->uid, "cID" => $this->typeId, "parent" => $this->typeId]);
        return view("exportExcel.sheet", [
            "prestations" => $prestations,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_EUR,
            'E' => NumberFormat::FORMAT_CURRENCY_EUR,
            'F' => NumberFormat::FORMAT_CURRENCY_EUR,
            'G' => NumberFormat::FORMAT_CURRENCY_EUR,
        ];
    }
}
