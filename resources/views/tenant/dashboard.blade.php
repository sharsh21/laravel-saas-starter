<x-layouts.tenant>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">Welcome back, {{ auth()->user()->name }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Team Members</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $tenant->users()->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Plan</p>
            <p class="text-3xl font-bold text-gray-900 mt-1 capitalize">{{ $tenant->plan }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-3xl font-bold mt-1 {{ $tenant->isSubscribed() ? 'text-green-600' : 'text-red-600' }}">
                {{ $tenant->isSubscribed() ? 'Active' : 'Inactive' }}
            </p>
        </div>
    </div>

    {{-- Add your tenant-specific content here --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Getting Started</h2>
        <p class="text-gray-600">Your application content goes here. Add Livewire components, tables, charts, and features as needed.</p>
    </div>
</x-layouts.tenant>
