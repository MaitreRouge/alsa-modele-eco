<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prestation extends Model
{
    use HasFactory;

    /**
     * @param string $nombre
     * @return string|null
     */
    private static function price(?float $nombre): ?string
    {
        if ($nombre === null) {
            return null;
        }

        // Vérifier si le nombre a une partie décimale
        if (str_contains($nombre, '.')) {
            // Séparer la partie entière et la partie décimale
            $parts = explode('.', $nombre);
            $decimales = $parts[1];

            // Si le nombre de décimales est supérieur à 2, tronquer à deux chiffres
            if (strlen($decimales) > 2) {
                $decimales = substr($decimales, 0, 2);
            }

            // Ajouter des zéros si nécessaire
            if (strlen($decimales) === 0) {
                $decimales = '00';
            } elseif (strlen($decimales) === 1) {
                $decimales .= '0';
            }

            // Reconstruire le nombre formaté
            $nombre = $parts[0] . '.' . $decimales;
        } else {
            // Si le nombre est un entier, ajouter '.00'
            $nombre .= '.00';
        }

        return $nombre . " €";
    }

    public function formatPrice(string $key): ?string
    {
        $nombre = $this->$key;
        return self::price($nombre);
    }

    public static function staticFormatPrice(?float $key)
    {
        $nombre = $key;
        return self::price($nombre);
    }

    public function mainCategory(): Categorie
    {
        $c = Categorie::find($this->idCategorie);
        while ($c->parentID != null) {
            $c = Categorie::find($c->parentID);
        }
        return $c;
    }

    public function categorie(): Categorie
    {
        return Categorie::find($this->idCategorie);
    }

    public function validEngagement($clientEng): bool
    {
//        dump($this->minEngagement);
        if (($this->minEngagement ?? $clientEng - 1) > $clientEng) return false;
        if (($this->maxEngagement ?? $clientEng + 1) < $clientEng) return false;
        return true;
    }

    public function formatEngagement(): string
    {
        if (!empty($this->minEngagement) and !empty($this->maxEngagement)) {
            if ($this->minEngagement === $this->maxEngagement) return $this->minEngagement . " mois";
            return "entre " . $this->minEngagement . " et " . $this->maxEngagement . " mois";
        }
        return "noFormat";
    }

    public function isAnOption(): bool
    {
        $option = DB::select("SELECT * FROM options WHERE option_id = :id", ["id" => $this->id]);
        return ($option !== []);
    }

    public function showBadges(): string
    {
        $html = "";
        if (!empty($this->note)) $html .= $this->createBadge("yellow", "Note");
        if ($this->isAnOption()) $html .= $this->createBadge("blue", "Option");
        if (!empty($this->promotionUntil)) $html .= $this->createBadge("green", "Promotion");
        if (!empty($this->minEngagement) or !empty($this->maxEngagement)) $html .= $this->createBadge("purple", "Engagement");
        return $html;
    }

    protected function createBadge(string $color, string $text): string
    {
        return '
            <span class="inline-flex items-center gap-x-1.5 rounded-md bg-'. $color .'-100 px-2 py-1 text-xs font-medium text-'. $color .'-700">
                <svg class="h-1.5 w-1.5 fill-'. $color .'-500" viewBox="0 0 6 6" aria-hidden="true">
                    <circle cx="3" cy="3" r="3" />
                </svg>
                '. $text .'
            </span>
        ';
    }
}
