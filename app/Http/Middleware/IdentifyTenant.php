<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $host      = $request->getHost();
        $central   = config('tenancy.central_domain');
        $subdomain = str($host)->before('.' . $central)->toString();

        // Request is on the central domain — no tenant context needed
        if ($host === $central || $subdomain === $host) {
            return $next($request);
        }

        $tenant = Tenant::where('subdomain', $subdomain)
            ->where('is_active', true)
            ->first();

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        app()->instance('tenant', $tenant);
        view()->share('tenant', $tenant);

        return $next($request);
    }
}
