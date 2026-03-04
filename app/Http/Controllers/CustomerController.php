<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
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
     * Update the specified customer in storage.
     *
     * @param Request $request
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        try {
            $data = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
                'phone' => 'nullable|string|max:50',
            ]);

            $oldData = $customer->only(['first_name', 'last_name', 'email', 'phone']);

            $customer->update($data);

            activity('customer')
                ->performedOn($customer)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old' => $oldData,
                    'new' => $customer->only(['first_name', 'last_name', 'email', 'phone'])
                ])
                ->log('Customer updated');

            Log::info('Customer updated successfully', [
                'user_id' => auth()->id(),
                'customer_id' => $customer->id
            ]);

            return redirect()->route('admin.customers.index')
                             ->with('success', 'Customer updated successfully.');

        } catch (Exception $e) {
            Log::error('Customer update failed', [
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to update customer. Please try again.');
        }
    }

    /**
     * Toggle the blocked status of the customer.
     *
     * @param Customer $customer
     * @return RedirectResponse
     */
    public function toggleBlock(Customer $customer): RedirectResponse
    {
        try {
            $newStatus = !$customer->is_blocked;

            $customer->update([
                'is_blocked' => $newStatus,
            ]);

            activity('customer')
                ->performedOn($customer)
                ->causedBy(auth()->user())
                ->withProperties([
                    'new_status' => $newStatus ? 'blocked' : 'unblocked'
                ])
                ->log('Customer block status toggled');

            Log::info('Customer block status updated', [
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'status' => $newStatus ? 'blocked' : 'unblocked'
            ]);

            return back()->with('success', 'Customer status updated successfully.');

        } catch (Exception $e) {
            Log::error('Customer block toggle failed', [
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to update customer status. Please try again.');
        }
    }

    /**
     * Handle bulk actions for customers (block or unblock selected customers).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulk(Request $request): RedirectResponse
    {
        try {
            if (!$request->filled('selected')) {
                Log::warning('Bulk action attempted without selecting customers', [
                    'user_id' => auth()->id()
                ]);

                return back()->with('error', 'No customers selected.');
            }

            $selectedIds = $request->selected;
            $customers = Customer::whereIn('id', $selectedIds);

            switch ($request->bulk_action) {
                case 'block':
                    $customers->update(['is_blocked' => true]);

                    activity('customer-bulk')
                        ->causedBy(auth()->user())
                        ->withProperties(['customer_ids' => $selectedIds])
                        ->log('Bulk block applied');
                    break;

                case 'unblock':
                    $customers->update(['is_blocked' => false]);

                    activity('customer-bulk')
                        ->causedBy(auth()->user())
                        ->withProperties(['customer_ids' => $selectedIds])
                        ->log('Bulk unblock applied');
                    break;

                default:
                    activity('customer-bulk')
                        ->causedBy(auth()->user())
                        ->withProperties([
                            'customer_ids' => $selectedIds,
                            'action' => $request->bulk_action
                        ])
                        ->log('Invalid bulk action attempted');

                    return back()->with('error', 'Invalid action.');
            }

            Log::info('Bulk action applied successfully', [
                'user_id' => auth()->id(),
                'customer_ids' => $selectedIds,
                'action' => $request->bulk_action
            ]);

            return back()->with('success', 'Bulk action applied successfully.');

        } catch (Exception $e) {
            Log::error('Bulk customer action failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to apply bulk action. Please try again.');
        }
    }
}