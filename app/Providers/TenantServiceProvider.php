<?php

namespace App\Providers;

use App\Http\Middleware\EnsureTenantIsSubscribed;
use App\Http\Middleware\EnsureTenantRole;
use App\Http\Middleware\IdentifyTenant;
use App\Services\TenantManager;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantManager::class);
    }

    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('tenant', IdentifyTenant::class);
        $this->app['router']->aliasMiddleware('subscribed', EnsureTenantIsSubscribed::class);
        $this->app['router']->aliasMiddleware('role', EnsureTenantRole::class);
    }
}
