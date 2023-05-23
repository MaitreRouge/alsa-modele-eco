<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    use HasFactory;

    public function price(string $key): ?string
    {
        $nombre = $this->$key;
        if ($nombre === null) { return null; }

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
}
