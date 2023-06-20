<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->cookie("token");
        if (!empty($token)) {
            return redirect("/dashboard");
        }

        return $next($request);
    }

}
