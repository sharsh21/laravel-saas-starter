<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->bound('tenant')) {
            return $next($request);
        }

        $tenant = app('tenant');

        if (! $tenant->isSubscribed()) {
            return redirect()->route('tenant.billing.index')
                ->with('warning', 'Your trial has expired. Please subscribe to continue.');
        }

        return $next($request);
    }
}
