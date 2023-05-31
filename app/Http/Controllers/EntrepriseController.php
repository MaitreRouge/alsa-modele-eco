<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Option;
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
            "raison-sociale" => [ "required" ],
            "rpap" => [ "required" ],
            "commercial" => [ "required" ],
            "agence" => [ "required", "numeric", Rule::in([2010, 2020, 2030, 2040, 2050, 2090]) ],
            "nb-sites" => [ "required", "numeric", "min:1" ],
            "engagement" => [ "required", Rule::in([12, 18, 24, 30, 36, 48, 60]) ],
            "upgrade" => [ "nullable" ],
            "nvSite" => [ "nullable" ],
            "nvClient" => [ "nullable" ],
            "name" => [ "required" ]
        ]);

        if (!empty($request["id"])) {
            $client = Client::find($request["id"]);
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
        $client->nvClient = !empty($request["nvClient"]);
        $client->save();
        return redirect("/edit/" . $client->id . "/fiche");
    }

    public function showMainPage(Request $request)
    {
        $client = Client::find($request["id"]);
        return view("fiches.main", ["client" => $client]);
    }

    public function showCategoryPage(Request $request)
    {
        $category = $request["category"];
        $category_number = $this->matchCategory($request["category"])-1;
        $client = Client::findOrFail($request["id"]);
        $prestations = DB::select("
        SELECT d.*
        FROM devis AS d, prestations as p, categories as c
        WHERE d.catalogueID = p.id
            AND d.version = p.version
            AND p.idCategorie = c.id
            AND c.parentID IN (SELECT c.id FROM categories as c WHERE c.parentID = :cID)
            AND d.clientID = :clientID
        ", ["clientID" => $client->id, "cID" => $category_number]);
//        $prestations = [];
        return view("fiches.resume-prestations", [
            "name" => ucfirst($category),
            "prestations" => $prestations,
            "cid" => $client->id,
            "subActive" => $category_number+1
        ]);
    }

    public function listPrestations(Request $request)
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
                $prestationsWithDetails = DB::table('prestations')
                    ->joinSub(function ($query) use ($parent) {
                        $query->from('prestations')
                            ->select('id', DB::raw('MAX(version) as version_max'))
                            ->where("idCategorie", $parent->id)
                            ->groupBy('id');
                    }, 't', function ($join) {
                        $join->on('prestations.id', '=', 't.id')
                            ->on('prestations.version', '=', 't.version_max');
                    })
                    ->leftJoin('options', 'prestations.id', '=', 'options.option_id')
                    ->whereNull('options.prestation_id')
                    ->get();
                $prestations[$parent->id] = $prestationsWithDetails;
            }
        }

        return view("fiches.liste-prestations", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "prestations" => $prestations, //Liste des prestations (affichés dans le tableau)
            "parents" => $parents, //Liste des parents des prestatons (affichés dans le tableau)
            "cid" => $request["id"], //Client (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être actif ()
            "categories" => $categories, //Liste de toutes les caregories (pour la liste déroulante),
            "main" => $main //Oui mais non
        ]);
    }

    public function showAddPrestations(Request $request)
    {
        $category = $this->matchCategory($request["category"]);

        $prestation = Prestation::where("id", $request["prestation"])
            ->orderBy("version", "DESC")
            ->limit(1)
            ->get();
        if (count($prestation) != 1) {
            return back(); //Multiple or no prestation found (if we trigger this, it should be none because the request limits to one anyway)
        }

        $options = Option::where("prestation_id", $prestation[0]->id)->get();

        return view("fiches.add-prestation", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "prestation" => $prestation[0],
            "cid" => $request["id"], //Client (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être actif
            "options" => $options
        ]);

    }

    public function processAddPrestations(Request $request)
    {
        $request->validate([
            "prixfas" => [ "nullable", "numeric", "min:0" ],
            "prixbrut" => [ "nullable", "numeric", "min:0" ],
            "prixmensuel" => [ "nullable", "numeric", "min:0" ],
            "qte" => [ "required", "numeric", "min:1" ]
        ]);

        $prestation = (Prestation::where("id", $request["prestation"])
            ->orderBy("version", "DESC")
            ->limit(1)
            ->get());
        if (count($prestation) != 1) {
            return back(); //Multiple or no prestation found (if we trigger this, it should be none because the request limits to one anyway)
        }
        $prestation = $prestation[0];

        $prices = ["brut" => NULL, "fas" => NULL, "mensuel" => NULL];

        if ($prestation->prixBrut != $request["prixbrut"]) $prices["brut"] = $request["prixbrut"];
        if ($prestation->prixMensuel != $request["prixmensuel"]) $prices["mensuel"] = $request["prixmensuel"];
        if ($prestation->prixFraisInstalation != $request["prixfas"]) $prices["fas"] = $request["prixfas"];

        $devis = new Devis();
        $devis->version = $prestation->version;
        $devis->catalogueID = $prestation->id;
        $devis->quantite = $request["qte"];
        $devis->prixBrut = $prices["brut"];
        $devis->prixMensuel = $prices["mensuel"];
        $devis->prixFraisInstalation = $prices["fas"];
        $devis->clientID = $request["id"];
        $devis->save();

        return redirect("/edit/".$request["id"]."/".$request["category"]);
    }

    public function showEditPrestations(Request $request)
    {
        $category = $this->matchCategory($request["category"]);

        $devis = Devis::findOrFail($request["prestation"]);
        if (empty($devis)) {
            return back(); //No prestation found
        }

        $prestation = Prestation::where("id", $devis->catalogueID)
            ->where("version", $devis->version)
            ->get();
        if (!isset($prestation[0]) or empty($prestation[0])) {
            throw new \Exception("La prestation associée au devis n'existe plus. Merci de contacter un administrateur");
        }

        return view("fiches.edit-prestation", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "devis" => $devis,
            "prestation" => $prestation[0],
            "cid" => $request["id"], //identifiant client dans l'url (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être acti
        ]);

    }

    public function processEditPrestations(Request $request)
    {
        $request->validate([
            "prixfas" => [ "nullable", "numeric", "min:0" ],
            "prixbrut" => [ "nullable", "numeric", "min:0" ],
            "prixmensuel" => [ "nullable", "numeric", "min:0" ],
            "qte" => [ "required", "numeric", "min:1" ]
        ]);

        $devis = Devis::findOrFail($request["prestation"]);

        $prestation = Prestation::where("id", $devis->catalogueID)
            ->where("version", $devis->version)
            ->get();
        if (!isset($prestation[0]) or empty($prestation[0])) {
            throw new \Exception("La prestation associée au devis n'existe plus. Merci de contacter un administrateur");
        }
        $prestation = $prestation[0];

        $prices = ["brut" => NULL, "fas" => NULL, "mensuel" => NULL];
        if ($prestation->prixBrut != $request["prixbrut"]) {
            $prices["brut"] = $request["prixbrut"];
        }
        if ($prestation->prixMensuel != $request["prixmensuel"]) {
            $prices["mensuel"] = $request["prixmensuel"];
        }
        if ($prestation->prixFraisInstalation != $request["prixfas"]) {
            $prices["fas"] = $request["prixfas"];
        }

        $devis->quantite = $request["qte"];
        $devis->prixBrut = $prices["brut"];
        $devis->prixMensuel = $prices["mensuel"];
        $devis->prixFraisInstalation = $prices["fas"];
        $devis->save();

        return redirect("/edit/".$request["id"]."/".$request["category"]);
    }

    public function deletePrestations(Request $request)
    {
        $devis = Devis::findOrFail($request["prestation"]);
//        dd($devis);
        $devis->delete();

        return redirect("/edit/". $request["id"] . "/" . $request["category"]);
    }

    public function deleteAll(Request $request)
    {
        $client = Client::findOrFail($request["id"]);
        DB::delete("DELETE FROM devis WHERE clientID = ". $request["id"]);
        $client->delete();
        return redirect("/dashboard");
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
