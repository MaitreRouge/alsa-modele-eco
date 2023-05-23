<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Prestation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class EntrepriseController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showDashboard(Request $request)
    {
        $clients = Client::all();
        return view("dashboard", ["clients" => $clients]);
    }

    public function showCreate(Request $request)
    {
        return view("create-modele");
    }

    public function processCreate(Request $request)
    {
        $request->validate([
            "raison-sociale" => [
                "required"
            ],
            "rpap" => [
                "required"
            ],
            "commercial" => [
                "required"
            ],
            "agence" => [
                "required",
                "numeric",
                Rule::in([2010, 2020, 2030, 2040, 2050, 2090])
            ],
            "nb-sites" => [
                "required",
                "numeric",
                "min:0"
            ],
            "engagement" => [
                "required",
                Rule::in([12, 18, 24, 30, 36, 48, 60])
            ],
            "upgrade" => [
                "nullable",
            ],
            "nvSite" => [
                "nullable",
            ],
            "nvClient" => [
                "nullable",
            ],
            "name" => [
                "required"
            ]
        ]);

        if (!empty($request["id"])) {
            $client = Client::find($request["id"]);
            if (!$client) return redirect("/dashboard");
        } else {
            $client = new Client;
        }

        $client->raisonSociale = $request["raison-sociale"];
        $client->RPAP = $request["rpap"];
        $client->nom = $request["name"];
        $client->dateSignature = $request["date-signature"];
        $client->datePremiereConnexion = $request["date-premiere-connxion"];
        $client->commercial = $request["commercial"];
        $client->agence = $request["agence"];
        $client->nbSites = $request["nb-sites"];
        $client->engagement = $request["engagement"];
        $client->upgrade = !empty($request["upgrade"]);
        $client->nvSite = !empty($request["nvSite"]);
//        $client->nvClient = !empty($request["nvClient"]);
        $client->nvClient = !empty($request["nvClient"]);
        $client->save();
        return redirect("/edit/" . $client->id . "/fiche");
    }

    public function showMainPage(Request $request)
    {
        $client = Client::find($request["id"]);
        if (!$client) return redirect("/dashboard");
        return view("fiches.main", ["client" => $client]);
    }

    public function showDataPage(Request $request)
    {
        $prestations = [];
        $client = Client::find($request["id"]);
        return view("fiches.prestations", ["name" => "Data", "prestations" => $prestations, "client" => $client, "subActive" => 2]);
    }

    public function showTelephoniePage(Request $request)
    {
        $prestations = [];
        $client = Client::find($request["id"]);
        return view("fiches.prestations", ["name" => "Telephonie", "prestations" => $prestations, "client" => $client, "subActive" => 3]);
    }

    public function showServicesPage(Request $request)
    {
        $prestations = [];
        $client = Client::find($request["id"]);
        return view("fiches.prestations", ["name" => "Services", "prestations" => $prestations, "client" => $client, "subActive" => 4]);
    }

    public function listPrestations(Request $request)
    {
        $request->validate([
            "id" => "exists|categories"
        ]);

        $client = Client::find($request["id"]);
        $category = match ($request["category"]) {
            "data" => 2,
            "telephonie" => 3,
            "services" => 4
        };
        $categories = Categorie::where("parentID", $category - 1)->get();

        $parents = [];
        $prestations = [];
        if (!empty($request["tri"])) {
            $parents = Categorie::where("parentID", $request["tri"])->get();
            foreach ($parents as $parent) {
                $prestations[$parent->id] = Prestation::where("idCategorie", $parent->id)->get();
            }
        }

        return view("fiches.prestationsadd", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "prestations" => $prestations, //Liste des prestations (affichés dans le tableau)
            "parents" => $parents, //Liste des parents des prestatons (affichés dans le tableau)
            "client" => $client, //Client (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être actif ()
            "categories" => $categories //Liste de toutes les caregories (pour la liste déroulante)
        ]);
    }

    public function showAddPrestations(Request $request)
    {

        $client = Client::findOrFail($request["id"]);

        $category = match ($request["category"]) {
            "data" => 2,
            "telephonie" => 3,
            "services" => 4
        };

        $prestation = Prestation::where("id", $request["prestation"])
            ->orderBy("version", "DESC")
            ->limit(1)
            ->get();
        if (count($prestation) != 1) {
            return back(); //Multiple or no prestation found (if we trigger this, it should be none because the request limits to one anyway)
        }

        return view("fiches.add-prestation", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "prestation" => $prestation[0],
//            "parents" => $parents, //Liste des parents des prestatons (affichés dans le tableau)
            "client" => $client, //Client (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être actif
//            "categories" => $categories //Liste de toutes les caregories (pour la liste déroulante)
        ]);

    }
    public function processAddPrestations(Request $request)
    {

        $client = Client::findOrFail($request["id"]);


        $prestation = (Prestation::where("id", $request["prestation"])
            ->orderBy("version", "DESC")
            ->limit(1)
            ->get());
        if (count($prestation) != 1) {
            return back(); //Multiple or no prestation found (if we trigger this, it should be none because the request limits to one anyway)
        }
        $prestation = $prestation[0];

        dd($prestation);
    }
}
