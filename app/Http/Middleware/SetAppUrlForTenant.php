<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function config;

class SetAppUrlForTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        config()->set('app.url', $request->getSchemeAndHttpHost());
        config()->set('app.asset_url', $request->getSchemeAndHttpHost());

        return $next($request);
    }
}
