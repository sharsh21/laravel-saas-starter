<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Cashier\Exceptions\IncompletePayment;

class BillingController extends Controller
{
    public function index(): View
    {
        $tenant     = app('tenant');
        $plans      = config('tenancy.plans');
        $intent     = $tenant->createSetupIntent();

        return view('tenant.billing.index', compact('tenant', 'plans', 'intent'));
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'plan'             => ['required', 'string', 'in:' . implode(',', array_keys(config('tenancy.plans')))],
            'payment_method'   => ['required', 'string'],
        ]);

        $tenant  = app('tenant');
        $plan    = config('tenancy.plans.' . $request->plan);

        try {
            $tenant->newSubscription('default', $plan['stripe_price_id'])
                ->trialDays($tenant->isOnTrial() ? 0 : config('tenancy.trial_days', 14))
                ->create($request->payment_method);

            $tenant->update(['plan' => $request->plan]);
        } catch (IncompletePayment $e) {
            return redirect()->route('cashier.payment', [$e->payment->id, 'redirect' => route('tenant.billing.index')]);
        }

        return redirect()->route('tenant.billing.index')
            ->with('success', 'Subscription activated successfully.');
    }

    public function portal(Request $request): RedirectResponse
    {
        return app('tenant')->redirectToBillingPortal(
            route('tenant.billing.index')
        );
    }

    public function cancel(Request $request): RedirectResponse
    {
        app('tenant')->subscription('default')->cancel();

        return redirect()->route('tenant.billing.index')
            ->with('success', 'Subscription cancelled. Access continues until the end of the billing period.');
    }
}
