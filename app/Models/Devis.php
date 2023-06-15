<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    private $prestation; //Variable de cache
    private $options; //Variable de cache

    public function getPrestation(): Prestation
    {
        if (empty($this->prestation)) $this->prestation = (Prestation::where("id", $this->catalogueID)->where("version", $this->version)->get())[0];
        return $this->prestation;
    }

    public function getSelectedOptions()
    {
        if (empty($this->options)) $this->options = Devis::where("optLinked", $this->id)->get();
        return $this->options;
    }

    public function isOptionSelected(int $id): bool
    {
        $this->getSelectedOptions();

        foreach ($this->options as $option) {
            if ($option->catalogueID === $id) return true;
        }
        return false;
    }
}
