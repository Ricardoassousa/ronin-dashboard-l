<?php

namespace App\Listeners;

use App\Events\CustomerCreated;
use App\Models\Activity;
use App\Models\User;
use App\Notifications\NewCustomerNotification;
use App\Notifications\WelcomeCustomerNotification;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleCustomerCreated
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
    public function handle($event)
    {
        $customer = $event->customer;

        try {
            Activity::create([
                'type' => 'customer',
                'description' => 'New registered customer: ' . $customer->first_name . ' ' . $customer->last_name,
                'user_id' => auth()->id()
            ]);
            Log::info('CustomerCreated Event: Activity registered', [
                'customer_id' => $customer->id,
                'created_by' => auth()->id()
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create activity for customer', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);
        }

        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewCustomerNotification($customer));
                Log::info('Notification sent to admin', [
                    'admin_id' => $admin->id,
                    'customer_id' => $customer->id
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to send notification to admins', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);
        }

        try {
            $customer->notify(new WelcomeCustomerNotification($customer));
            Log::info('Welcome notification sent to customer', [
                'customer_id' => $customer->id
            ]);
        } catch (Exception $e) {
            Log::error('Failed to send welcome notification to customer', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);
        }
    }

}
