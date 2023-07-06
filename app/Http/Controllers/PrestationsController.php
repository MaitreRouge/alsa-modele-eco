<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Changelog;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Historique;
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
        $prestation->id = (Prestation::orderby("id", "DESC")->limit(1)->get("id"))[0]->id + 1;
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

    public function processMassDelete(Request $request)
    {
        foreach ($request->toArray() as $key => $value) {
            if ($value === null) {
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
        if (!empty($p)) return redirect("/prestations/" . strtolower($p->mainCategory()->label) . "?tri=" . $p->getCategory()->parentCategory()->id);
        return back();
    }

    private function matchCategory(string $c)
        /********** FONCTIONS PRIVÉS **********/
    {
        return match (strtolower($c)) {
            "data" => 2,
            "telephonie" => 3,
            "services" => 4
        };
    }
}

