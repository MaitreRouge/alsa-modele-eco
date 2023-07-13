<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Changelog;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Historique;
use App\Models\Notification;
use App\Models\Option;
use App\Models\Prestation;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class PrestationsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showList(Request $request)
    {

//        dd("nop");

        $category = $this->matchCategory($request["category"]);
        $categories = Categorie::where("parentID", $category - 1)->get();

        $parents = [];
        $prestations = [];
        $main = null;
        if (!empty($request["tri"])) {
            $main = Categorie::findOrFail($request["tri"]);
            $parents = Categorie::where("parentID", $request["tri"])->get();
            foreach ($parents as $parent) {
                $prestationsWithDetails = Prestation::
                joinSub(function ($query) use ($parent) {
                    $query->from('prestations')
                        ->select('id', DB::raw('MAX(version) as version_max'))
                        ->where("idCategorie", $parent->id)
                        ->where("disabled", null)
                        ->groupBy('id');
                }, 't', function ($join) {
                    $join->on('prestations.id', '=', 't.id')
                        ->on('prestations.version', '=', 't.version_max');
                })
                    ->get();
                $prestations[$parent->id] = $prestationsWithDetails;
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

    public function showEdit(Request $request)
    {
        if ($request["deleteOption"]) {
            Option::where("option_id", $request["deleteOption"])->where("prestation_id", $request["prestation"])->delete();
//            $o->delete();
        }

        $prestation = Prestation::where("id", $request["prestation"])
            ->orderby("version", "DESC")
            ->limit(1)
            ->get();
        $prestation = $prestation[0];
        $parents = Categorie::where("parentID", $prestation->categorie()->parentID)->get();

        return view("prestations.edit", [
            "prestation" => $prestation,
            "subActive" => $prestation->mainCategory()->id,
            "parents" => $parents
        ]);
    }

    public function processEdit(Request $request)
    {
//        dump($request->toArray());
        $request->validate([
            "label" => ["required", "max:100"],
            "prixFAS" => ["nullable"],
            "prixmensuel" => ["nullable"],
            "note" => ["nullable"],
            "maxEngagement" => ["nullable", "numeric"],
            "minEngagement" => ["nullable", "numeric"]
        ]);

        $prestation = Prestation::where("id", $request["prestation"])
            ->orderby("version", "DESC")
            ->limit(1)
            ->first();
        $newPrestation = new Prestation();
        $newPrestation->version += $prestation->version + 1;
        $newPrestation->id = $prestation->id;
        $newPrestation->label = $request["label"];
        $newPrestation->prixMensuel = $request["prixmensuel"];
        $newPrestation->prixFAS = $request["prixFAS"];
        $newPrestation->note = $request["note"];
        $newPrestation->idCategorie = $request["category"];
        $newPrestation->minEngagement = $request["minEngagement"];
        $newPrestation->maxEngagement = $request["maxEngagement"];
        $newPrestation->updated_at = Carbon::now();
        $newPrestation->idCategorie = $prestation->idCategorie;
        $newPrestation->save();

        $log = new Historique();
        $log->catalogueID = $prestation->id;
        $log->newVersion = $newPrestation->version;
        $log->type = "edition";
        $log->uid = User::fromToken(Cookie::get("token"))->id;
        $log->save();

        return redirect("/prestations/");
    }

    public function showCategoryEdit(Request $request)
    {

        $category = Categorie::findOrFail($request["cid"]);
        return view("prestations.categoryedit", [
            "category" => $category,
            "subActive" => $category->rootCategory()->id,
            "parents" => Categorie::where("parentID", $category->rootCategory()->id)->get()
        ]);

    }

    public function showCategoryNew(Request $request)
    {

        $rootCatID = $this->matchCategory($request["category"]) - 1;
        return view("prestations.categoryedit", [
            "category" => null,
            "subActive" => $rootCatID,
            "parents" => Categorie::where("parentID", $rootCatID)->get(),
            "parent" => $request["parent"]
        ]);

    }

    public function showParentNew(Request $request)
    {

        $rootCatID = $this->matchCategory($request["category"]) - 1;
        return view("prestations.categoryedit", [
            "category" => null,
            "subActive" => $rootCatID,
            "parents" => [Categorie::find($rootCatID)],
            "parent" => 0
        ]);

    }

    public function processCategoryEdit(Request $request)
    {

        $category = Categorie::findOrFail($request["cid"]);
        $category->label = $request["label"];
        $category->parentID = $request["parent"];
        $category->note = $request["note"];
        $category->update();
        return redirect("/prestations");

    }

    public function processCategoryNew(Request $request)
    {

        $category = new Categorie();
        $category->label = $request["label"];
        $category->parentID = $request["parent"] ?? ($this->matchCategory($request["category"]) - 1);
        $category->note = $request["note"];
        $category->save();
        return redirect("/prestations/" . $request["category"] . "?tri=" . $request["parent"]);

    }

    public function showNew(Request $request)
    {
        $parent = ($this->matchCategory($request["category"])) - 1;
        $parents = (Categorie::where("parentID", $request["sub"])->get());

//        dd($parents);

        return view("prestations.edit", [
            "subActive" => $parent,
            "parents" => $parents
        ]);
    }

    public function processNew(Request $request)
    {
        $request->validate([
            "label" => "required|max:100",
            "parent" => "required|exists:categories,id",
            "prixFAS" => "nullable|min:0|decimal:0,2",
            "prixmensuel" => "nullable|min:0|decimal:0,2",
        ]);

//        dd();
        $prestation = new Prestation();
        $prestation->id = (((Prestation::orderby("id", "DESC")->limit(1)->get("id"))[0]->id)??0) + 1;
        $prestation->label = $request["label"];
        $prestation->version = 1;
        $prestation->prixFAS = $request["prixFAS"];
        $prestation->prixMensuel = $request["prixmensuel"];
        $prestation->idCategorie = $request["parent"];
        $prestation->note = $request["note"];
        $prestation->minEngagement = $request["minEngagement"];
        $prestation->maxEngagement = $request["maxEngagement"];
        $prestation->save();

        $uid = (User::fromToken(Cookie::get("token"))->id);
        $hist = new Historique();
        $hist->catalogueID = $prestation->id;
        $hist->newVersion = 1;
        $hist->type = "creation";
        $hist->uid = $uid;
        $hist->save();
//        dd($request->toArray());
        return redirect("prestations/" . $request["category"] . "?tri=" . $request["sub"]);
    }

    public function showBulkEdit(Request $request)
    {

//        $category = $this->matchCategory($request["category"]);
//        $categories = Categorie::where("parentID", $category - 1)->get();

        $main = Categorie::findOrFail($request["tri"]);
        $category = $main->rootCategory();
        $categories = Categorie::where("parentID", $category->id)->get();

        $parents = [];
        $prestations = [];
        if (!empty($request["tri"])) {
            $main = Categorie::findOrFail($request["tri"]);
            $parents = Categorie::where("parentID", $request["tri"])->get();
//            dd($parents);
            foreach ($parents as $parent) {
                $prestationsWithDetails = Prestation::
                joinSub(function ($query) use ($parent) {
                    $query->from('prestations')
                        ->select('id', DB::raw('MAX(version) as version_max'))
                        ->where("idCategorie", $parent->id)
                        ->where("disabled", null)
                        ->groupBy('id');
                }, 't', function ($join) {
                    $join->on('prestations.id', '=', 't.id')
                        ->on('prestations.version', '=', 't.version_max');
                })
                    ->get();
                $prestations[$parent->id] = $prestationsWithDetails;
            }
        }

        return view("prestations.bulkedit", [
            "name" => ucfirst("bulk edit"), //Nom de la page
            "prestations" => $prestations, //Liste des prestations (affichés dans le tableau)
            "parents" => $parents, //Liste des parents des prestatons (affichés dans le tableau)
            "subActive" => $category->id + 1, //Index du bouton du sous-menu qui doit être actif ()
            "categories" => $categories, //Liste de toutes les caregories (pour la liste déroulante),
            "main" => null //Oui mais non
        ]);

    }

    public function processBulkEdit(Request $request)
    {
        $champs = ["prixMensuel", "prixFAS", "label", "note", "minEngagement", "maxEngagement"];

        $category = Categorie::findOrFail($request["tri"]);

        foreach ($request->toArray() as $key => $value) {
            if ($key !== "_token") { //On récupère tous les champs sauf le token csrf
                $pieces = explode("-", $key); //On les sépare car leurs nom est "presta-ID-NOM"
                if (!in_array($pieces[2], $champs)) {
                    return redirect()->back()->withErrors(["security" => "Vous essayez de modifier une propriété qui est protégée (:"]);
                }

                $data[$pieces[1]][$pieces[2]] = $value;
            }
        }

        foreach ($data as $id => $properties) {
            $dirty = 0; // La fonction isDirty de laravel est buggée et renvoie tjrs true (06/06/2023)
            $old = Prestation::where("id", $id)
                ->orderby("version", "DESC")
                ->limit(1)
                ->first();
            $prestation = new Prestation();

            $prestation->id = $old->id;

            foreach ($properties as $key => $value) {
                if ($old->$key != $value) {
                    $dirty = 1;
                }
                $prestation->$key = $value;
            }
            if ($dirty) {
                $prestation->version += $old->version + 1;
                $prestation->idCategorie += $old->idCategorie;
                $prestation->updated_at = Carbon::now();
                $prestation->save();

                $log = new Historique();
                $log->catalogueID = $prestation->id;
                $log->newVersion = $prestation->version;
                $log->type = "edition";
                $log->uid = User::fromToken(Cookie::get("token"))->id;
                $log->save();
            }
        }

        return redirect("/prestations/" . strtolower($category->rootCategory()->label) . "?tri=" . $category->id);
    }

    public function processDelete(Request $request)
    {
        $prestation = Prestation::where("id", $request["id"])
            ->where("version", 1)
            ->first();
        if (!empty($prestation)) {
            DB::update("UPDATE prestations SET disabled = 1 WHERE id = :id", ["id" => $request->id]);
            $log = new Historique();
            $log->catalogueID = $prestation->id;
            $log->newVersion = null;
            $log->type = "deletion";
            $log->uid = User::fromToken(Cookie::get("token"))->id;
            $log->save();
        }
        return redirect("/prestations/" . strtolower($prestation->mainCategory()->label) . "?tri=" . $prestation->getCategory()->parentCategory()->id);
    }

    public function processCategoryDelete(Request $request)
    {
        $category = Categorie::findOrFail($request["id"]);

        foreach ($category->getPrestations() as $prestation) {
            DB::update("UPDATE prestations SET disabled = 1 WHERE id = :id", ["id" => $request->id]);
            $log = new Historique();
            $log->catalogueID = $prestation->id;
            $log->newVersion = null;
            $log->type = "deletion";
            $log->uid = User::fromToken(Cookie::get("token"))->id;
            $log->save();
        }

        $category->delete();
        return redirect("/prestations/" . strtolower($category->rootCategory()->label));
    }

    public function processMassDelete(Request $request)
    {
//            dump($request->toArray());
        foreach ($request->toArray() as $key => $value) {
            if ($value === "on") {
                $id = explode("-", $key)[1];
                $p = Prestation::where("id", $id)->first();
                if (!empty($p)) {
                    DB::update("UPDATE prestations SET disabled = 1 WHERE id = :id", ["id" => $id]);
                    $log = new Historique();
                    $log->catalogueID = $id;
                    $log->newVersion = null;
                    $log->type = "deletion";
                    $log->uid = User::fromToken(Cookie::get("token"))->id;
                    $log->save();
                }
            }
        }

//        dd("end");
        if (!empty($p)) return redirect("/prestations/" . strtolower($p->mainCategory()->label) . "?tri=" . $p->getCategory()->parentCategory()->id);
        return back();
    }

    public function showAddOptions(Request $request)
    {

        if ($request["singlePrestation"]) {
            session(["addOptionsPrestations" => [$request["singlePrestation"]]]);
        }

        $idPres = session("addOptionsPrestations");
        $prestation = Prestation::where("id", $idPres[0])->orderby("version", "DESC")->first();
        $main = $prestation->categorie()->parentCategory();
        $prestations = [];
        $parents = Categorie::where("parentID", $main->id)->get();
        foreach ($parents as $parent) {
            $prestationsWithDetails = Prestation::joinSub(function ($query) use ($parent) {
                $query->from('prestations')
                    ->select('id', DB::raw('MAX(version) as version_max'))
                    ->where('idCategorie', $parent->id)
                    ->whereNull('disabled')
                    ->groupBy('id');
            }, 't', function ($join) {
                $join->on('prestations.id', '=', 't.id')
                    ->on('prestations.version', '=', 't.version_max');
            })
                ->whereNotIn('prestations.id', function ($query) {
                    $query->select('prestation_id')
                        ->from('options');
                })
                ->whereNotIn('prestations.id', $idPres)
                ->get();
            $prestations[$parent->id] = $prestationsWithDetails;
        }
//            dd($prestations);

        return view("prestations.addOptions", [
            "prestations" => $prestations, //Liste des prestations (affichés dans le tableau)
            "parents" => $parents, //Liste des parents des prestatons (affichés dans le tableau)
            "subActive" => $main->rootCategory()->id, //Index du bouton du sous-menu qui doit être actif ()
        ]);
    }

    public function processAddOptions(Request $request)
    {
        $arr = $request->toArray();

        $idPres = [];
        $idOpts = [];
        $containsOptions = false;
        $containsPrestations = false;
        foreach ($arr as $key => $value) {
            if (str_contains($key, "prest")) {
                $idPres[] = (explode("-", $key))[1];
            }
            if (str_contains($key, "opt")) {
                $idOpts[] = (explode("-", $key))[1];
            }
        }

//        dd($idPres, $idOpts);

        if (count($idOpts) >= 1 and count($idPres) === 0) {
            $idPres = session("addOptionsPrestations");
            if (count($idPres) < 1) {
                return redirect("/prestations/");
            }
        }

        if (count($idPres) === 0) {
            return redirect()->back();
        }

        if (count($idOpts) === 0) {
            session(["addOptionsPrestations" => $idPres]);
            return redirect("/prestations/addOptions");
        }

        foreach ($idOpts as $opt) {
            foreach ($idPres as $pre) {
                $newOpt = new Option();
                $newOpt->option_id = $opt;
                $newOpt->prestation_id = $pre;
                $newOpt->obligatoire = 0;
                $newOpt->save();
            }
        }

        session(["addOptionsPrestations" => []]);

        if (count($idPres) === 1) return redirect("/prestations/edit/" . $idPres[0]);
        return redirect("/prestations");

//        dd("On a des options et des prestations");


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

