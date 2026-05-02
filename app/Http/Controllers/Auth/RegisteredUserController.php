<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TenantManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private TenantManager $tenantManager) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'company'  => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tenant = $this->tenantManager->create([
            'name'       => $request->company,
            'email'      => $request->email,
            'owner_name' => $request->name,
            'password'   => $request->password,
        ]);

        $user = $tenant->users()->where('role', 'owner')->first();

        event(new Registered($user));
        Auth::login($user);

        // Redirect to the tenant's subdomain dashboard
        return redirect()->away('http://' . $tenant->domain() . '/dashboard');
    }
}
