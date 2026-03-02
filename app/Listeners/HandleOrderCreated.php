<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleOrderCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        try {
            Activity::create([
                'type' => 'order',
                'description' => 'New order #' . $order->id . ' from ' . $order->customer->first_name . ' ' . $order->customer->last_name,
                'user_id' => auth()->id()
            ]);

            Log::info('OrderCreated Event: Activity registered', [
                'order_id' => $order->id,
                'customer_id' => $order->customer->id
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create activity for order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

        foreach ($order->orderItems as $item) {
            try {
                Activity::create([
                    'type' => 'product',
                    'description' => 'Product "' . $item->product->name . '" added to order #' . $order->id,
                    'user_id' => auth()->id()
                ]);

                Log::info('Activity registered for product in order', [
                    'order_id' => $order->id,
                    'product_id' => $item->product->id
                ]);
            } catch (Exception $e) {
                Log::error('Failed to create activity for product in order', [
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewOrderNotification($order));
                Log::info('Notification sent to admin', [
                    'admin_id' => $admin->id,
                    'order_id' => $order->id
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to notify admins about order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

}
