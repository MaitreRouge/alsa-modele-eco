<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Notification;
use App\Models\Option;
use App\Models\Prestation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
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
            "raison-sociale" => ["required"],
            "rpap" => ["required"],
            "commercial" => ["required"],
            "agence" => ["required", "numeric", Rule::in([2010, 2020, 2030, 2040, 2050, 2090])],
            "nb-sites" => ["required", "numeric", "min:1"],
            "engagement" => ["required", Rule::in([12, 18, 24, 30, 36, 48, 60])],
            "upgrade" => ["nullable"],
            "nvSite" => ["nullable"],
            "nvClient" => ["nullable"],
            "name" => ["required"]
        ]);

        $client = new Client;
        if (!empty($request["id"])) $client = Client::find($request["id"]);

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

        // Vérification des prestations liés au client et si l'engagement est toujours respecté
        $devis = Devis::where("clientID", $client->id)->get();
        if (!empty($devis) and count($devis) > 0) {
            foreach ($devis as $d) {
                $d->conflict = !($d->getPrestation()->validEngagement($client->engagement));
                $d->save();
            }
        }

        $notification = new Notification();
        $notification->title = "Client bien créé !";
        $notification->save();

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
        $category_number = $this->matchCategory($category) - 1;
        $prestations = DB::select("
        SELECT DISTINCT d.*
        FROM devis AS d, prestations as p, categories as c
        WHERE (d.catalogueID = p.id
            AND d.version = p.version
            AND p.idCategorie = c.id
            AND c.parentID IN (SELECT c.id FROM categories as c WHERE c.parentID = :cID)
            AND d.clientID = :clientID)
            OR d.parent = :parent
        ", ["clientID" => $request["id"], "cID" => $category_number, "parent" => $category_number]);
        return view("fiches.resume-prestations", [
            "name" => ucfirst($category),
            "prestations" => $prestations,
            "cid" => $request["id"],
            "subActive" => $category_number + 1
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
            "client" => Client::findOrFail($request["id"]), //Client (necessaire pour les redirections et pour l'engagement)
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
        $prestation = $prestation[0];

        $options = Option::where("prestation_id", $prestation->id)->get();
        $client = Client::find($request["id"]);

        if (!$prestation->validEngagement($client->engagement)) {
            $notification = new Notification();
            $notification->title = "Engagement invalide pour cette prestation !";
            $notification->color = "red";
            $notification->icon = "exclamation-circle";
            $notification->save();
            return back();
        }

        return view("fiches.add-prestation", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "prestation" => $prestation,
            "client" => $client, //Client (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être actif
            "options" => $options
        ]);

    }

    public function processAddPrestations(Request $request)
    {
        $category = $this->matchCategory($request["category"]) - 1;

        $request->validate([
            "prixfas" => ["nullable", "numeric"],
            "prixbrut" => ["nullable", "numeric"],
            "prixmensuel" => ["nullable", "numeric"],
            "qte" => ["required", "numeric", "min:1"],
            "customName" => Rule::requiredIf($request["prestation"] === 0)
        ]);

        /******************************/
        /*   Ajout de la prestation   */
        /******************************/
        $prestation = (Prestation::where("id", $request["prestation"])
            ->orderBy("version", "DESC")
            ->limit(1)
            ->get());
        if (count($prestation) != 1) {
            return back(); //Aucune prestation trouvée, on revient en arrière
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
        if ($prestation->id === 0) {
            $devis->parent = $category;
            $devis->customName = ($request["customName"])??"Autre prestation - Nom à changer";
        }
        $devis->save();

        $notification = new Notification();
        $notification->title = "Prestation #".$prestation->id." enregistrée !";
        $notification->save();

        /*******************************/
        /*      Ajout des options      */
        /*******************************/

        foreach ($request->toArray() as $key => $value) {
            if (str_contains($key, "opt-")) {
                // Chaque option qui à été ajoutée est représentée de la manière ["opt-ID" => true]
                // Donc on prend la clée, la coupe à "-" et prends la partie de droite.
                // Ca nous donne cet $opt_id
                $opt_id = explode("-", $key)[1];

                $option = (Prestation::where("id", $opt_id)
                    ->orderBy("version", "DESC")
                    ->limit(1)
                    ->get());
                if (count($option) != 1) {
                    return back(); //Aucune prestation trouvée, on revient en arrière
                }

                $option = $option[0];
                $devisOpt = new Devis();
                $devisOpt->version = $option->version;
                $devisOpt->catalogueID = $option->id;
                $devisOpt->quantite = 1;
                $devisOpt->prixBrut = $option->prixBrut;
                $devisOpt->prixMensuel = $option->prixMensuel;
                $devisOpt->prixFraisInstalation = $option->prixFraisInstalation;
                $devisOpt->optLinked = $devis->id;
                $devisOpt->clientID = $request["id"];
                $devisOpt->save();

                $notification = new Notification();
                $notification->title = "Option #".$option->id." enregistrée !";
                $notification->save();
            }
        }

        return redirect("/edit/" . $request["id"] . "/" . $request["category"]);
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
            "prixfas" => ["nullable", "numeric"],
            "prixbrut" => ["nullable", "numeric"],
            "prixmensuel" => ["nullable", "numeric"],
            "qte" => ["required", "numeric", "min:1"],
            "customName" => Rule::requiredIf($request["prestation"] === 0)
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
        if ($prestation->prixBrut != $request["prixbrut"]) $prices["brut"] = $request["prixbrut"];
        if ($prestation->prixMensuel != $request["prixmensuel"]) $prices["mensuel"] = $request["prixmensuel"];
        if ($prestation->prixFraisInstalation != $request["prixfas"]) $prices["fas"] = $request["prixfas"];

        $devis->quantite = $request["qte"];
        $devis->prixBrut = $prices["brut"];
        $devis->prixMensuel = $prices["mensuel"];
        $devis->prixFraisInstalation = $prices["fas"];
        if ($prestation->id === 0) {
            $devis->customName = $request["customName"];
        }
        $devis->save();

        $notification = new Notification();
        $notification->title = "Entrée #".$devis->id." modifiée !";
        $notification->save();

        return redirect("/edit/" . $request["id"] . "/" . $request["category"]);
    }

    public function deletePrestations(Request $request)
    {
        $devis = Devis::findOrFail($request["prestation"]);
        DB::delete("DELETE FROM devis WHERE optLinked = " . $devis->id);
        $devis->delete();

        $notification = new Notification();
        $notification->title = "Prestation #".$devis->id." supprimé !";
        $notification->save();

        return redirect("/edit/" . $request["id"] . "/" . $request["category"]);
    }

    public function deleteAll(Request $request)
    {
        $client = Client::findOrFail($request["id"]);
        DB::delete("DELETE FROM devis WHERE clientID = " . $request["id"]);
        $client->delete();

        $notification = new Notification();
        $notification->title = "Fiche client supprimée !";
        $notification->save();

        return redirect("/dashboard");
    }

    public function deleteAllDevis(Request $request)
    {
        $client = Client::findOrFail($request["id"]);
        DB::delete("DELETE FROM devis WHERE clientID = " . $request["id"]);

        $notification = new Notification();
        $notification->title = "Tous les devis du client ont bien été supprimés";
        $notification->save();

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
