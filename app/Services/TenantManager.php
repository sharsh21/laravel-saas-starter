<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantManager
{
    public function create(array $data): Tenant
    {
        return DB::transaction(function () use ($data) {
            $tenant = Tenant::create([
                'name'          => $data['name'],
                'subdomain'     => $this->generateSubdomain($data['name']),
                'email'         => $data['email'],
                'plan'          => 'trial',
                'trial_ends_at' => now()->addDays(config('tenancy.trial_days', 14)),
                'is_active'     => true,
            ]);

            // Create the owner user without the tenant scope
            $user = User::withoutGlobalScopes()->create([
                'tenant_id' => $tenant->id,
                'name'      => $data['owner_name'],
                'email'     => $data['email'],
                'password'  => bcrypt($data['password']),
                'role'      => 'owner',
            ]);

            return $tenant;
        });
    }

    public function generateSubdomain(string $name): string
    {
        $base = Str::slug($name);
        $subdomain = $base;
        $counter = 1;

        while (Tenant::where('subdomain', $subdomain)->exists()) {
            $subdomain = $base . '-' . $counter++;
        }

        return $subdomain;
    }

    public function suspend(Tenant $tenant): void
    {
        $tenant->update(['is_active' => false]);
    }

    public function activate(Tenant $tenant): void
    {
        $tenant->update(['is_active' => true]);
    }
}
