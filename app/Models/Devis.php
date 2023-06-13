<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    public function getPrestation(): Prestation
    {
        return (Prestation::where("id", $this->catalogueID)->where("version", $this->version)->get())[0];
    }
}
