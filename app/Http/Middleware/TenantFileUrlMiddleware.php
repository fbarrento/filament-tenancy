<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function config;
use function tenant;

class TenantFileUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        config()->set(
            'filesystems.disks.public.url',
            url('/'.config()->string('tenancy.filesystem.suffix_base').tenant('id'))
        );

        return $next($request);
    }
}
