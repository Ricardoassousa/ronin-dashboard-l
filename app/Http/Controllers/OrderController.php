<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
            Log::info('Accessing order index', [
                'user_id' => auth()->id(),
                'filters' => [
                    'customer' => $request->customer,
                    'status' => $request->status,
                ]
            ]);

            $query = Order::with('customer');

            // Filter by customer name (First Name or Last Name)
            if ($request->filled('customer')) {
                $searchTerm = $request->customer;

                $query->whereHas('customer', function ($q) use ($searchTerm) {
                    $q->where(function ($subQuery) use ($searchTerm) {
                        $subQuery->where('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                        // Optional: Search for the full name combined (MySQL specific)
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
                    });
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $orders = $query->latest()->paginate(10)->withQueryString();

            return view('admin.orders.index', compact('orders'));

        } catch (Exception $e) {
            Log::error('Error accessing order index', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'There was a problem retrieving the orders.');
        }
    }

    /**
     * Display the specified order with related items.
     *
     * @param Order $order
     * @return View|RedirectResponse
     */
    public function show(Order $order)
    {
        try {
            Log::info('Viewing order details', [
                'user_id' => auth()->id(),
                'order_id' => $order->id
            ]);

            $order->load('orderItems.product', 'customer');

            return view('admin.orders.show', compact('order'));

        } catch (Exception $e) {
            Log::error('Error viewing order details', [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to load order details.');
        }
    }

    /**
     * Update the status of the specified order.
     *
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        try {
            $data = $request->validate([
                'status' => 'required|in:pending,paid,shipped,cancelled',
            ]);

            $oldStatus = $order->status;

            $order->update(['status' => $data['status']]);

            activity('order-status')
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $data['status'],
                ])
                ->log('Order status changed');

            return back()->with('success', 'Order status updated successfully.');

        } catch (Exception $e) {
            Log::error('Error updating order status', [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to update order status.');
        }
    }
}