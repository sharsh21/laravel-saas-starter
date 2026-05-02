<x-layouts.tenant>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-xl">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Workspace Settings</h2>
        <form method="POST" action="{{ route('tenant.settings.update') }}">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                    <input type="text" name="name" value="{{ old('name', $tenant->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Billing Email</label>
                    <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subdomain</label>
                    <div class="flex items-center gap-2">
                        <input type="text" value="{{ $tenant->subdomain }}"
                            class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-500 cursor-not-allowed"
                            disabled>
                        <span class="text-sm text-gray-500 whitespace-nowrap">.{{ config('tenancy.central_domain') }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Subdomains cannot be changed after creation.</p>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-layouts.tenant>
