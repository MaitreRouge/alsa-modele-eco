<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    use HasFactory;

    private $histories = null;

    public function fetchAllHistories() {
        if ($this->histories === null) $this->histories = Historique::where("changelogID", $this->id)->orderBy("id", "DESC")->get();
        return $this->histories;
    }
}
