<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Notification;
use App\Models\Option;
use App\Models\Prestation;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EntrepriseController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showDashboard(Request $request)
    {
        $clients = Client::all();
        $user = User::fromToken(Cookie::get("token"));
        return view("dashboard", ["clients" => $clients, "tempPwd" => $user->tempPwd]);
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
            "commercial" => ["required", "numeric", "exists:users,id"],
            "agence" => ["required", "numeric", Rule::in([2010, 2020, 2030, 2040, 2050, 2090])],
            "nb-sites" => ["required", "numeric", "min:1"],
            "engagement" => ["required", "numeric", "min:1"],
            "upgrade" => ["nullable"],
            "nvSite" => ["nullable"],
            "nvClient" => ["nullable"],
            "nbNvSites" => ["nullable","numeric","min:1"],
            "name" => ["required"]
        ]);

        if ($request["nbNvSites"] > $request["nb-sites"] and $request["nvSite"]) {
            return back()->withErrors(["Le nombre de nouveaux sites est supérieur au nombre de sites totaux du clients"]);
        }

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
        if ($client->nvSite) $client->nbNvSites = $request["nbNvSites"]??0;
        else $client->nbNvSites = 0;
        $client->save();

        $oldNvSiteFAS = Devis::where("clientID", $client->id)
            ->where("catalogueID", 1)
            ->first();

        if ($client->nvSite) {
            if ($oldNvSiteFAS) $oldNvSiteFAS->delete();

            $devis = new Devis();
            $devis->version = 1;
            $devis->quantite = $client->nbNvSites;
            $devis->clientID = $client->id;
            $devis->catalogueID = 1;
            $devis->parent = 1;
            $devis->save();
        }

        if($oldNvSiteFAS and !$client->nvSite) $oldNvSiteFAS->delete();

        $notification = new Notification();
        $notification->title = "Client bien créé/modifié !";
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
        WHERE ((d.catalogueID = p.id
            AND d.version = p.version
            AND p.idCategorie = c.id
            AND c.parentID IN (SELECT c.id FROM categories as c WHERE c.parentID = :cID)
            )
            OR d.parent = :parent)
            AND d.clientID = :clientID
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
                            ->where("disabled", null)
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
            "cid" =>$request["id"],
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

        return view("fiches.add-prestation", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "prestation" => $prestation,
            "client" => $client, //Client (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être actif
        ]);

    }

    public function processAddPrestations(Request $request)
    {
        $category = $this->matchCategory($request["category"]) - 1;

        $request->validate([
            "prixfas" => ["nullable", "numeric"],
            "pdvmensuel" => ["nullable", "numeric"],
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

        $prices = ["fas" => $prestation->prixFAS, "mensuel" => $prestation->prixMensuel];

        if ($prestation->prixMensuel != $request["pdvmensuel"]) $prices["mensuel"] = $request["pdvmensuel"];
        if ($prestation->prixFAS != $request["pdvfas"]) $prices["fas"] = $request["pdvfas"];

        $devis = new Devis();
        $devis->version = $prestation->version;
        $devis->catalogueID = $prestation->id;
        $devis->quantite = $request["qte"];
        $devis->pdvMensuel = $prices["mensuel"];
        $devis->pdvFAS = $prices["fas"];
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
                $devisOpt->pdvMensuel = $option->prixMensuel;
                $devisOpt->pdvFAS = $option->prixFAS;
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

        $prestation = $devis->getPrestation();
        $options = Option::where("prestation_id", $prestation->id)->get();


        return view("fiches.add-prestation", [
            "name" => ucfirst($request["category"]), //Nom de la page
            "devis" => $devis,
            "prestation" => $prestation,
            "cid" => $request["id"], //identifiant client dans l'url (necessaire pour les redirections)
            "subActive" => $category, //Index du bouton du sous-menu qui doit être activé
        ]);

    }

    public function processEditPrestations(Request $request)
    {
        $request->validate([
            "pdvfas" => ["nullable", "numeric"],
            "pdvmensuel" => ["nullable", "numeric"],
            "qte" => ["required", "numeric", "min:1"],
            "customName" => Rule::requiredIf($request["prestation"] === 0)
        ]);

        $devis = Devis::findOrFail($request["prestation"]);

        /**************************************/
        /*      Edition de la prestation      */
        /**************************************/

        $prestation = Prestation::where("id", $devis->catalogueID)
            ->where("version", $devis->version)
            ->get();
        if (!isset($prestation[0]) or empty($prestation[0])) {
            throw new \Exception("La prestation associée au devis n'existe plus. Merci de contacter un administrateur");
        }
        $prestation = $prestation[0];

        $prices = ["fas" => NULL, "mensuel" => NULL];
        if ($prestation->prixMensuel != $request["pdvmensuel"]) $prices["mensuel"] = $request["pdvmensuel"];
        if ($prestation->prixFAS != $request["pdvfas"]) $prices["fas"] = $request["pdvfas"];

        $devis->quantite = $request["qte"];
        $devis->pdvMensuel = $prices["mensuel"];
        $devis->pdvFAS = $prices["fas"];
        if ($prestation->id === 0) {
            $devis->customName = $request["customName"];
        }
        $devis->save();

        /*********************************/
        /*      Édition des options      */
        /*********************************/

        $opts_ids = [];
        foreach ($request->toArray() as $key => $value) {
            if (str_contains($key, "opt-")) {
                // Chaque option qui à été ajoutée est représentée de la manière ["opt-ID" => true]
                // Donc on prend la clée, la coupe à "-" et prends la partie de droite.
                // Ca nous donne cet $opt_id
                $id = explode("-", $key)[1];
                $opts_ids[$id] = $id;
            }
        }

        //Avec cette boucle, on supprime les options qui n'ont pas été selectionnés MAIS qui sont dans le devis et
        //on retire de $opts_ids les identifiants des options qui ont été selctionnés et qui sont déjà dans le devis
        foreach ($devis->getSelectedOptions() as $option) {
            if (!in_array($option->catalogueID, $opts_ids)) {
                $option->delete();

                $notification = new Notification();
                $notification->title = "Option #".$option->catalogueID." supprimée !";
                $notification->save();
            } else {
                unset($opts_ids[$option->catalogueID]);
            }
        }

        //Maintenant $opts_ids ne possède uniquement des ids d'options qui viennent d'être selectionnées MAIS
        //qui ne sont pas (encore) dans la base de donnée
        foreach ($opts_ids as $opt_id) {
            $option = (Prestation::where("id", $opt_id)
                ->orderBy("version", "DESC")
                ->limit(1)
                ->get());
            if (count($option) != 1) {
                //En vrai, on pourrait faire un retour en arrière, on pourrait afficher une notification mais
                //étant donné qu'on est VRAIMENT PAS censé rentrer dans cette condition, je préfère mettre un throw
                //car ca serait inquétant et que je pense que le problème devrait immédiatement être remonté :/
                throw new \Exception("L'option selectionnée (#$id) n'existe pas ou est dupliquée...");
            }

            $option = $option[0];
            $devisOpt = new Devis();
            $devisOpt->version = $option->version;
            $devisOpt->catalogueID = $option->id;
            $devisOpt->quantite = 1;
            $devisOpt->pdvMensuel = $option->prixMensuel;
            $devisOpt->pdvFAS = $option->prixFAS;
            $devisOpt->optLinked = $devis->id;
            $devisOpt->clientID = $request["id"];
            $devisOpt->save();

            $notification = new Notification();
            $notification->title = "Option #" . $option->id . " enregistrée !";
            $notification->save();

        }
//            }

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
