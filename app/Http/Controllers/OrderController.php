<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     *
     * @return View
     */
    public function index(): View
    {
        $orders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order with related items.
     *
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        $order->load('orderItems.product', 'customer');

        return view('admin.orders.show', compact('order'));
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
        $data = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);

        $order->update([
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

}