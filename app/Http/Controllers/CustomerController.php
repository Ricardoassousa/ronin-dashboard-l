<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->when($request->name, fn($q) =>
                $q->where('name', 'like', "%{$request->name}%")
            )
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified customer.
     *
     * @param Customer $customer
     * @return View
     */
    public function show(Customer $customer): View
    {
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param Customer $customer
     * @return View
     */
    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     *
     * @param Request $request
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
        ]);

        $customer->update($data);

        return redirect()->route('admin.customers.index')
                         ->with('success', 'Customer updated successfully.');
    }

    /**
     * Toggle the blocked status of the customer.
     *
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function toggleBlock(Customer $customer): RedirectResponse
    {
        $customer->update([
            'is_blocked' => !$customer->is_blocked,
        ]);

        return back()->with('success', 'Customer status updated successfully.');
    }

}