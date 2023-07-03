<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    public function getOldPrestation(): ?Prestation
    {
        return Prestation::where("id", $this->catalogueID)->where("version", $this->newVersion - 1)->first();
    }

    public function getNewPrestation(): Prestation
    {
        return Prestation::where("id", $this->catalogueID)->where("version", $this->newVersion)->first();
    }
}
