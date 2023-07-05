<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class Badge extends Model
{
    protected $connection = null;

    public static string $deletion = "red";
    public static string $edition = "yellow";
    public static string $creation = "green";

    public static function create(string $title, string $color = "red", bool $dot = true): string
    {
        $dotHTML = null;
        if ($dot) {
            $dotHTML = "<svg class=\"h-1.5 w-1.5 fill-$color-500\" viewBox=\"0 0 6 6\" aria-hidden=\"true\">
                    <circle cx=\"3\" cy=\"3\" r=\"3\" />
                  </svg>";
        }

        return "<span class=\"inline-flex items-center gap-x-1.5 rounded-md bg-$color-100 px-2 py-1 text-xs font-medium text-$color-700\">
                  $dotHTML
                  $title
                </span>";
    }

    public static function color(string $keyword){
        return Badge::$$keyword;
    }
}
