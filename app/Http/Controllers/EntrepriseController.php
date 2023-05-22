<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
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
            ]
        ]);

        $client = new Client;
        $client->raisonSociale = $request["raison-sociale"];
        $client->RPAP = $request["rpap"];
        $client->dateSignature = $request["date-signature"];
        $client->datePremiereConnexion = $request["date-premiere-connxion"];
        $client->commercial = $request["commercial"];
        $client->agence = $request["agence"];
        $client->nbSites = $request["nb-sites"];
        $client->engagement = $request["engagement"];
        $client->upgrade = !empty($request["upgrade"]);
        $client->nvSite = !empty($request["nvSite"]);
        $client->nvClient = !empty($request["commercial"]);
        $client->save();
        return redirect("/edit/".$client->id);
    }

    public function showMainPage(Request $request)
    {
        return view("edit-modele");
    }
}
