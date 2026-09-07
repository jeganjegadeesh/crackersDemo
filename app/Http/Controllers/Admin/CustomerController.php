<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::where("role", "customer")
            ->when($request->filled("q"), fn ($q) => $q->where("name", "like", "%" . $request->q . "%"))
            ->latest()->paginate(20)->withQueryString();

        return view("admin.customers.index", compact("customers"));
    }

    public function show(User $customer)
    {
        abort_unless($customer->role === "customer", 404);
        $customer->load("orders");

        return view("admin.customers.show", compact("customer"));
    }

    public function toggleStatus(User $customer)
    {
        abort_unless($customer->role === "customer", 404);
        $customer->update(["status" => ! $customer->status]);

        return back()->with("status", "Customer status updated.");
    }
}
