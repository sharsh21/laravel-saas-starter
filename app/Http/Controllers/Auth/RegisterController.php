<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private TenantManager $tenantManager) {}

    public function showForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'company'    => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $tenant = $this->tenantManager->create([
            'name'       => $data['company'],
            'email'      => $data['email'],
            'owner_name' => $data['owner_name'],
            'password'   => $data['password'],
        ]);

        $user = $tenant->users()->where('role', 'owner')->first();
        Auth::login($user);

        return redirect()->away(
            'http://' . $tenant->domain() . route('tenant.dashboard', [], false)
        );
    }
}
