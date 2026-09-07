<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view("customer.profile.edit", ["user" => $request->user()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "phone" => ["required", "string", "max:20"],
            "email" => ["required", "email", "unique:users,email," . $request->user()->id],
        ]);

        $request->user()->update($validated);

        return back()->with("status", "Profile updated.");
    }
}
