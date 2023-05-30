<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    protected $keyType = null;

    private Prestation $prestation;


    public function getPrestation()
    {
        if (!empty($prestation)) return $prestation;
        return (Prestation::where("id", $this->option_id)
            ->orderBy("version", "DESC")
            ->limit(1)
            ->get())[0];
    }
}
