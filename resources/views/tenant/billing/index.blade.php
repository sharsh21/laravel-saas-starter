<x-layouts.tenant>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Billing</h1>
    </div>

    {{-- Current status --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Current Plan</h2>
        @if($tenant->subscribed('default'))
            <p class="text-green-700 font-medium">Active — {{ ucfirst($tenant->plan) }} plan</p>
            <p class="text-sm text-gray-500 mt-1">
                Renews {{ $tenant->subscription('default')->asStripeSubscription()->current_period_end | date('M j, Y') }}
            </p>
            <div class="mt-4 flex gap-3">
                <a href="{{ route('tenant.billing.portal') }}" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                    Manage in Stripe Portal
                </a>
                <form method="POST" action="{{ route('tenant.billing.cancel') }}">
                    @csrf
                    <button class="text-sm text-red-600 hover:text-red-800">Cancel subscription</button>
                </form>
            </div>
        @elseif($tenant->isOnTrial())
            <p class="text-yellow-700 font-medium">
                Free trial — expires {{ $tenant->trial_ends_at->format('M j, Y') }}
                ({{ $tenant->trial_ends_at->diffForHumans() }})
            </p>
            <p class="text-sm text-gray-500 mt-1">Subscribe below to continue after your trial ends.</p>
        @else
            <p class="text-red-700 font-medium">No active subscription</p>
        @endif
    </div>

    {{-- Plans --}}
    @unless($tenant->subscribed('default'))
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Choose a Plan</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach($plans as $key => $plan)
        <div class="bg-white rounded-xl border-2 {{ $key === 'growth' ? 'border-blue-500' : 'border-gray-200' }} p-6 relative">
            @if($key === 'growth')
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-blue-500 text-white text-xs px-3 py-1 rounded-full">Most Popular</span>
            @endif
            <h3 class="text-lg font-bold text-gray-900">{{ $plan['name'] }}</h3>
            <p class="text-3xl font-bold text-gray-900 mt-2">${{ $plan['price'] }}<span class="text-base font-normal text-gray-500">/mo</span></p>
            <ul class="mt-4 space-y-2">
                @foreach($plan['features'] as $feature)
                    <li class="text-sm text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>
            <button
                onclick="selectPlan('{{ $key }}')"
                class="mt-6 w-full py-2 px-4 rounded-lg text-sm font-medium {{ $key === 'growth' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                Choose {{ $plan['name'] }}
            </button>
        </div>
        @endforeach
    </div>

    {{-- Stripe payment form --}}
    <div id="payment-form" class="bg-white rounded-xl border border-gray-200 p-6 hidden">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h2>
        <form action="{{ route('tenant.billing.subscribe') }}" method="POST" id="subscribe-form">
            @csrf
            <input type="hidden" name="plan" id="selected-plan">
            <input type="hidden" name="payment_method" id="payment-method-id">
            <div id="card-element" class="border border-gray-300 rounded-lg p-3 mb-4"></div>
            <div id="card-errors" class="text-red-600 text-sm mb-4"></div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-medium">
                Subscribe Now
            </button>
        </form>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('cashier.key') }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        function selectPlan(plan) {
            document.getElementById('selected-plan').value = plan;
            document.getElementById('payment-form').classList.remove('hidden');
            document.getElementById('payment-form').scrollIntoView({ behavior: 'smooth' });
        }

        document.getElementById('subscribe-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const { setupIntent, error } = await stripe.confirmCardSetup(
                '{{ $intent->client_secret }}',
                { payment_method: { card } }
            );
            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                document.getElementById('payment-method-id').value = setupIntent.payment_method;
                e.target.submit();
            }
        });
    </script>
    @endunless
</x-layouts.tenant>
