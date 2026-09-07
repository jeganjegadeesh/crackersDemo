<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view("customer.auth.login");
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);

        if (! Auth::attempt($credentials, $request->boolean("remember"))) {
            return back()->withErrors([
                "email" => "The provided credentials do not match our records.",
            ])->onlyInput("email");
        }

        $request->session()->regenerate();

        if ($request->user()->isAdmin()) {
            return redirect()->intended(route("admin.dashboard"));
        }

        return redirect()->intended(route("home"));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard("web")->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect("/");
    }
}
