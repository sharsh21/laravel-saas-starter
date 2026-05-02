<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tenant->name }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased">

<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-6">
                <span class="font-semibold text-gray-900">{{ $tenant->name }}</span>
                <a href="{{ route('tenant.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                <a href="{{ route('tenant.settings.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Settings</a>
            </div>
            <div class="flex items-center gap-4">
                @if($tenant->isOnTrial())
                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                        Trial ends {{ $tenant->trial_ends_at->diffForHumans() }}
                    </span>
                @endif
                <a href="{{ route('tenant.billing.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Billing</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-gray-600 hover:text-gray-900">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4 text-sm">
            {{ session('warning') }}
        </div>
    @endif

    {{ $slot }}
</main>

@livewireScripts
</body>
</html>
