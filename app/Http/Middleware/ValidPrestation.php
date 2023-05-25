<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Devis;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class ValidPrestation
{
    /**
     * Handle an incoming request and remove the lash / if there is one.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $devis = Devis::find($request["prestation"]);

        if (empty($devis)){
            return back();
        }

        return $next($request);
    }
}
