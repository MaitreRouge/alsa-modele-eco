<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Changelog;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Historique;
use App\Models\Prestation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class PrestationsController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showList(Request $request)
    {
//        $request->validate([
//            "id" => "exists:categories"
//        ]);

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
                    ->get();
//                dd($prestationsWithDetails);
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
        $categories = Categorie::where("parentID", $prestation->categorie()->parentID)->get();
//        dd($categories);

        return view("prestations.edit", [
            "prestation" => $prestation,
            "subActive" => $prestation->mainCategory()->id,
            "categories" => $categories
        ]);
    }

    public function processEdit(Request $request)
    {
//        dump($request->toArray());
        $request->validate([
            "label" => ["required", "max:100"],
            "category" => ["required", "exists:categories,id"],
            "prixbrut" => ["nullable"],
            "prixFAS" => ["nullable"],
            "prixmensuel" => ["nullable"],
            "note" => ["nullable"]
        ]);

        $prestation = Prestation::where("id", $request["prestation"])
            ->orderby("version", "DESC")
            ->limit(1)
            ->get();
        $prestation = $prestation[0];
        $newPrestation = $prestation->replicate();
        $newPrestation->version += 1;
        $newPrestation->id = $prestation->id;
        $newPrestation->label = $request["label"];
        $newPrestation->prixBrut = $request["prixbrut"];
        $newPrestation->prixMensuel = $request["prixmensuel"];
        $newPrestation->prixFraisInstalation = $request["prixFAS"];
        $newPrestation->note = $request["note"];
        $newPrestation->needPrixVente = $request["prixVente"] ?? false;
        $newPrestation->idCategorie = $request["category"];
        $newPrestation->updated_at = Carbon::now();
        $newPrestation->save();

        $log = new Historique();
        $log->catalogueID = $prestation->id;
        $log->newVersion = $newPrestation->version;
        $log->save();

        return redirect("/prestations/");
    }

    public function showNew(Request $request)
    {
        $parent = ($this->matchCategory($request["category"])) - 1;

        return view("prestations.new", [
            "categories" => Categorie::where("parentId", $request["sub"])->get(),
            "subActive" => $parent
        ]);
    }

    public function processNew(Request $request)
    {
        $request->validate([
            "label" => "required|max:100",
            "parent" => "required|exists:categories,id",
            "prixbrut" => "nullable|min:0|decimal:0,2",
            "prixFAS" => "nullable|min:0|decimal:0,2",
            "prixmensuel" => "nullable|min:0|decimal:0,2",
            "prixVente" => "nullable"
        ]);

//        dd();
        $prestation = new Prestation();
        $prestation->id = (Prestation::orderby("id", "DESC")->limit(1)->get("id"))[0]->id + 1;
        $prestation->label = $request["label"];
        $prestation->version = 1;
        $prestation->prixBrut = $request["prixbrut"];
        $prestation->prixFraisInstalation = $request["prixFAS"];
        $prestation->prixMensuel = $request["prixmensuel"];
        $prestation->idCategorie = $request["parent"];
        $prestation->note = $request["note"];
        $prestation->needPrixVente = $request["prixVente"]??0;
        $prestation->save();

        $hist = new Historique();
        $hist->catalogueID = $prestation->id;
        $hist->newVersion = 1;
        $hist->save();
//        dd($request->toArray());
        return redirect("prestations/". $request["category"] ."?tri=" . $request["sub"]);
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
