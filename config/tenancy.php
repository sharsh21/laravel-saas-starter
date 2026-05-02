<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Central Domain
    |--------------------------------------------------------------------------
    | The root domain of your SaaS. Tenants are served on subdomains.
    | Example: if central_domain is "app.com", tenants are at "acme.app.com"
    */
    'central_domain' => env('CENTRAL_DOMAIN', 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Trial Period (days)
    |--------------------------------------------------------------------------
    */
    'trial_days' => env('TENANT_TRIAL_DAYS', 14),

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    | Define your Stripe plans here. The key is used internally,
    | stripe_price_id maps to your Stripe Dashboard price ID.
    */
    'plans' => [
        'starter' => [
            'name'            => 'Starter',
            'stripe_price_id' => env('STRIPE_PLAN_STARTER'),
            'price'           => 29,
            'currency'        => 'USD',
            'interval'        => 'month',
            'features'        => [
                'Up to 5 users',
                '10GB storage',
                'Email support',
            ],
        ],
        'growth' => [
            'name'            => 'Growth',
            'stripe_price_id' => env('STRIPE_PLAN_GROWTH'),
            'price'           => 79,
            'currency'        => 'USD',
            'interval'        => 'month',
            'features'        => [
                'Unlimited users',
                '100GB storage',
                'Priority support',
                'API access',
            ],
        ],
        'enterprise' => [
            'name'            => 'Enterprise',
            'stripe_price_id' => env('STRIPE_PLAN_ENTERPRISE'),
            'price'           => 199,
            'currency'        => 'USD',
            'interval'        => 'month',
            'features'        => [
                'Unlimited users',
                'Unlimited storage',
                'Dedicated support',
                'Custom integrations',
                'SLA guarantee',
            ],
        ],
    ],

];
