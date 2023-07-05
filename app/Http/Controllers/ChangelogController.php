<?php

namespace App\Http\Controllers;

use App\Models\Changelog;
use App\Models\Historique;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ChangelogController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showMain(Request $request)
    {
        return view("changelogs.main", [
            "changelogs" => Changelog::select("*")->orderby("id", "DESC")->get()
        ]);
    }

    public function showNew(Request $request)
    {
        return view("changelogs.create", [
            "histories" => Historique::where("changelogID", null)->get()
        ]);
    }

    public function processNew(Request $request)
    {
        $request->validate([
            "nomChangelog" => "required",
            "description" => "required"
        ]);
//        dump("Valid request");

        $changelog = new Changelog();
        $changelog->titre = $request["nomChangelog"];
        $changelog->description = $request["description"];
        $changelog->uid = (User::fromToken(Cookie::get("token")))->id;
        $changelog->save();
//        dump("Changelog id is : " . $changelog->id);
//        die;

        $historyArray = [];
        foreach ($request->toArray() as $title => $value) {
//            dd($title);
            if (str_contains($title, "history")) {
                $history = Historique::find(explode("-", $title)[1]);
                $history->changelogID = $changelog->id;
                $history->save();
                $historyArray[] = $history->id;
                dump("ChangelogID changed for history with ID : " . $history->id);
            }
        }
//        dump("Next dump will include all histories ID included in this changelog");
//        dump($historyArray);

        if (count($historyArray) === 0) {
            $changelog->delete();
        }

        return redirect("/changelog/");
    }
}
