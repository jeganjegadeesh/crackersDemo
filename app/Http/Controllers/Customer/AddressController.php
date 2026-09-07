<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        return view("customer.profile.addresses", ["addresses" => $request->user()->addresses]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "phone" => ["required", "string", "max:20"],
            "address" => ["required", "string"],
            "city" => ["required", "string", "max:100"],
            "state" => ["required", "string", "max:100"],
            "pincode" => ["required", "string", "max:10"],
            "is_default" => ["nullable", "boolean"],
        ]);

        if ($request->boolean("is_default")) {
            $request->user()->addresses()->update(["is_default" => false]);
        }

        $request->user()->addresses()->create($validated);

        return back()->with("status", "Address added.");
    }

    public function destroy(Request $request, Address $address)
    {
        abort_unless($address->user_id === $request->user()->id, 403);
        $address->delete();

        return back()->with("status", "Address removed.");
    }
}
