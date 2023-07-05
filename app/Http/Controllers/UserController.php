<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // You can use this controller as a base for others controllers

    public function showLogin(Request $request): View
    {

        return view("user.login");
    }

    public function processLogin(Request $request): RedirectResponse
    {
        $request->validate([
            "email" => ["required","email"],
            "password" => ["required"]
        ]);

        if (!(str_contains($request["email"], "@alsatis.com") or str_contains($request["email"], "@ineonet.com"))) {
            return redirect("/login")->withErrors(["Seuls les adresses mail @alsatis.com ou @ineonet.com peuvent être utilisés pour se connecter"]);
        }

        $user = ((User::where("email", $request["email"])->get())[0])??null;

        if (empty($user)) {
            return redirect("/login")->withErrors(["Adresse mail ou mot de passe invalide"]);
        }

        if (!$user->passwordVerify($request["password"])) {
            return redirect("/login")->withErrors(["Adresse mail ou mot de passe invalide"]);
        }

        $token = new UserToken();
        $token->token = Str::random(120);
        $token->uid = $user->id;
        $token->validUntil = (Carbon::now())->addHours(4);
        $token->save();

        return redirect("/dashboard")->withCookie(cookie("token", $token->token, 60*9, "/"));
    }

    public function processLogout(Request $request): RedirectResponse
    {
        return redirect("/dashboard")->withCookie(cookie("token", null, -1, "/"));
    }

    public function showList()
    {
        $users = User::all();
        return view("user.list", ["users" => $users]);
    }

    public function showCreate()
    {
        return view("user.create");
    }

    public function processCreate(Request $request)
    {
        $request->validate([
            "nom" => ["required", "alpha_dash"],
            "prenom" => ["required", "alpha_dash"],
            "email" => ["required"],
            "at_email" => ["required", Rule::in(["alsatis", "ineonet"])],
            "role" => ["required", Rule::in(["u", "a"])],
            "temppassword" => ["required", "min:8"]
        ]);
//        dump($request->toArray());

        $user = User::where("email", $request["email"] . "@" . $request["at_email"] . ".com")->get();

        if (count($user) > 0) {
            return back()->withErrors(["Un utilisateur existe déjà avec cette adresse mail."]);
        }

        $user = new User();
        $user->nom = $request["nom"];
        $user->prenom = $request["prenom"];
        $user->email = ($request["email"] . "@" . $request["at_email"] . ".com");
        $user->password = password_hash($request["temppassword"], PASSWORD_BCRYPT);
        $user->role = ($request["role"]==="a")?"admin":"user";
        $user->save();

        return redirect("/users/list");

//        dd("EOF");
    }
}
