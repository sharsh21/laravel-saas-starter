<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

class Tenant extends Model
{
    use Billable;

    protected $fillable = [
        'name',
        'subdomain',
        'email',
        'plan',
        'trial_ends_at',
        'is_active',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'is_active'     => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function owner(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'owner');
    }

    public function domain(): string
    {
        return $this->subdomain . '.' . config('tenancy.central_domain');
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isSubscribed(): bool
    {
        return $this->subscribed('default') || $this->isOnTrial();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
