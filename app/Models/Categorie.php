<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categorie extends Model
{
    use HasFactory;

    public function parentCategory(): ?Categorie
    {
        return Categorie::find($this->parentID);
    }

    public function rootCategory(): Categorie
    {
        $main = $this;
        while ($main != null) {
            $c = $main->parentCategory();
            if ($c == null) {
                return $main;
            }
            $main = $c;
        }
        return $main;
    }

    public function getPrestationsInsideCategory()
    {
        //TODO: J'ai pas envie de le faire maintenant
        return null;
    }

    public function getPrestationsIdsInsideCategory()
    {
        $results = DB::select("SELECT DISTINCT id FROM prestations WHERE idCategorie = ?", [$this->id]);
        $ids = [];
        foreach ($results as $r){
            $ids[] = $r->id;
        }
        return $ids;
    }
}
