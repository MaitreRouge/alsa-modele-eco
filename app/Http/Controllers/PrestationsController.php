<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Prestation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class PrestationsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showList(Request $request)
    {
        $request->validate([
            "id" => "exists|categories"
        ]);

        $category = $this->matchCategory($request["category"]);
        $categories = Categorie::where("parentID", $category - 1)->get();

        $parents = [];
        $prestations = [];
        $main = null;
        if (!empty($request["tri"])) {
            $main = Categorie::findOrFail($request["tri"]);
            $parents = Categorie::where("parentID", $request["tri"])->get();
            foreach ($parents as $parent) {
                $prestations[$parent->id] = Prestation::where("idCategorie", $parent->id)->get();
            }
        }

        return view("prestations.main", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "prestations" => $prestations, //Liste des prestations (affichés dans le tableau)
            "parents" => $parents, //Liste des parents des prestatons (affichés dans le tableau)
            "cid" => $request["id"], //Client (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être actif ()
            "categories" => $categories, //Liste de toutes les caregories (pour la liste déroulante),
            "main" => $main //Oui mais non
        ]);
    }

    /********** FONCTIONS PRIVÉS **********/
    private function matchCategory(string $c)
    {
        return match (strtolower($c)) {
            "data" => 2,
            "telephonie" => 3,
            "services" => 4
        };
    }
}
