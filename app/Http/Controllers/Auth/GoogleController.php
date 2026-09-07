<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver("google")->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver("google")->stateless()->user();

        $user = User::where("google_id", $googleUser->getId())
            ->orWhere("email", $googleUser->getEmail())
            ->first();

        if ($user) {
            // Link the Google account to an existing email/password account if needed.
            if (! $user->google_id) {
                $user->update(["google_id" => $googleUser->getId()]);
            }
        } else {
            $user = User::create([
                "name" => $googleUser->getName() ?: $googleUser->getNickname() ?: "Google User",
                "email" => $googleUser->getEmail(),
                "google_id" => $googleUser->getId(),
                "password" => Hash::make(Str::random(32)),
                "role" => "customer",
            ]);

            Cart::create(["user_id" => $user->id]);
        }

        if (! $user->status) {
            return redirect()->route("login")->withErrors([
                "email" => "This account has been disabled. Please contact support.",
            ]);
        }

        Auth::login($user, remember: true);

        if ($user->isAdmin()) {
            return redirect()->intended(route("admin.dashboard"));
        }

        return redirect()->intended(route("home"));
    }
}
