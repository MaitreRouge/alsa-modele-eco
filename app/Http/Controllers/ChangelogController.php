<?php

namespace App\Http\Controllers;

use App\Models\Historique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ChangelogController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showMain(Request $request){
        return view("changelogs.main");
    }

    public function showNew(Request $request){
        return view("changelogs.create", [
            "histories" => Historique::where("changelogID", null)->get()
        ]);
    }
}
