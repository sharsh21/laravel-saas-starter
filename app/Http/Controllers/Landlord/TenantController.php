<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(private TenantManager $tenantManager) {}

    public function index(): View
    {
        $tenants = Tenant::withCount('users')
            ->latest()
            ->paginate(25);

        return view('landlord.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load('users');

        return view('landlord.tenants.show', compact('tenant'));
    }

    public function suspend(Tenant $tenant): RedirectResponse
    {
        $this->tenantManager->suspend($tenant);

        return back()->with('success', "Tenant {$tenant->name} suspended.");
    }

    public function activate(Tenant $tenant): RedirectResponse
    {
        $this->tenantManager->activate($tenant);

        return back()->with('success', "Tenant {$tenant->name} activated.");
    }
}
