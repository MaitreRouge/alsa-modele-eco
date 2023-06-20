<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticated
{
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {

        $token = $request->cookie("token");
//        dd($token);
        if (empty($token)) {
            return redirect("/login");
        }

        $token = UserToken::find($token);
        if (empty($token)) {
            cookie("token", null, -1);
            return redirect("/login")->withErrors(["La session a expirée et il faut se reconnecter"]);
        }

        if ($token->validUntil < Carbon::now()) {
            cookie("token", null, -1);
            return redirect("/login")->withErrors(["La session a expirée et il faut se reconnecter"]);
        }

        if (!empty($role) and $token->user()->role !== $role) {
            return back()->withErrors(["Vous n'avez pas la permission pour effectuer cette action"]);
        }

        return $next($request);
    }
}
