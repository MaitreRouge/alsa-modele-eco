<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class ValidClientId
{
    /**
     * Handle an incoming request and remove the lash / if there is one.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $client = Client::find($request["id"]);

        if (empty($client)){
            return back();
        }

        return $next($request);
    }
}
