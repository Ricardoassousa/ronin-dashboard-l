<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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
        Log::info('Accessing customer index', [
            'user_id' => auth()->id(),
            'filter_name' => $request->name
        ]);

        $customers = Customer::query()
            ->when($request->name, fn($q) =>
                $q->where('first_name', 'like', "%{$request->name}%")
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
        Log::info('Viewing customer details', [
            'user_id' => auth()->id(),
            'customer_id' => $customer->id
        ]);

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
        Log::info('Accessing customer edit page', [
            'user_id' => auth()->id(),
            'customer_id' => $customer->id
        ]);

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

        $oldData = $customer->only('name', 'email');

        $customer->update($data);

        Log::info('Customer updated successfully', [
            'user_id' => auth()->id(),
            'customer_id' => $customer->id,
            'old_data' => $oldData,
            'new_data' => $customer->fresh()->only('name', 'email')
        ]);

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
        $newStatus = !$customer->is_blocked;

        $customer->update([
            'is_blocked' => $newStatus,
        ]);

        Log::info('Customer block status toggled', [
            'user_id' => auth()->id(),
            'customer_id' => $customer->id,
            'new_status' => $newStatus ? 'blocked' : 'unblocked'
        ]);

        return back()->with('success', 'Customer status updated successfully.');
    }

    /**
     * Handle bulk actions for customers (block or unblock selected customers).
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function bulk(Request $request): RedirectResponse
    {
        // Check if any customers were selected
        if (!$request->filled('selected')) {

            Log::warning('Bulk action attempted without selecting customers', [
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'No customers selected.');
        }

        $selectedIds = $request->selected;

        // Retrieve the selected customers
        $customers = Customer::whereIn('id', $selectedIds);

        switch ($request->bulk_action) {

            case 'block':
                $customers->update(['is_blocked' => true]);

                Log::info('Bulk block action applied', [
                    'user_id' => auth()->id(),
                    'customer_ids' => $selectedIds
                ]);
                break;

            case 'unblock':
                $customers->update(['is_blocked' => false]);

                Log::info('Bulk unblock action applied', [
                    'user_id' => auth()->id(),
                    'customer_ids' => $selectedIds
                ]);
                break;

            default:

                Log::warning('Invalid bulk action attempted', [
                    'user_id' => auth()->id(),
                    'action' => $request->bulk_action,
                    'customer_ids' => $selectedIds
                ]);

                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', 'Bulk action applied successfully.');
    }

}