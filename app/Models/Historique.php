<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    private ?Prestation $cachedOldPrestation = null;
    private ?Prestation $cachedNewPrestation = null;

    public function getOldPrestation(): ?Prestation
    {
        if ($this->newVersion === null) return null;
        if ($this->cachedOldPrestation === null) $this->cachedOldPrestation = Prestation::where("id", $this->catalogueID)->where("version", $this->newVersion - 1)->first();
        return $this->cachedOldPrestation;
    }

    public function getNewPrestation(): ?Prestation
    {
        if ($this->newVersion === null) return null;
        if ($this->cachedNewPrestation === null) $this->cachedNewPrestation = Prestation::where("id", $this->catalogueID)->where("version", $this->newVersion)->first();
        return $this->cachedNewPrestation;
    }

    public function getChanges()
    {
//        dump()
        $properties = ["prixMensuel", "prixFAS", "label", "note", "minEngagement", "maxEngagement"];
        $changes = [];
        foreach ($properties as $property) {
            if ($this->getNewPrestation()->$property !== $this->getOldPrestation()->$property) {
                $changes[] = $property;
            }
        }
        return $changes;
    }

    public function getSafeLabel()
    {
        return $this->getNewPrestation()->label??$this->getLastPrestation()->label;
    }

    private function getLastPrestation()
    {
        return Prestation::where("id", $this->catalogueID)->orderBy("version", "DESC")->first();
    }
}
