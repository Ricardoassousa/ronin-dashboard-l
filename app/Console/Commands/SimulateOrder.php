<?php

namespace App\Console\Commands;

use App\Events\OrderCreated;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Console\Command;

class SimulateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulates the registration of a new order';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customer = Customer::inRandomOrder()->first();

        if (!$customer) {
            $this->warn('No customers found. Creating a new customer...');

            $customer = Customer::factory()->create();

            $this->info("Customer #{$customer->id} created.");
        }

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => 'pending',
            'total_amount' => 0,
            'placed_at' => now()
        ]);

        $items = OrderItem::factory()->count(3)->create([
            'order_id' => $order->id,
            'unit_price' => rand(10, 100),
            'quantity' => rand(1, 5),
            'total_price' => function ($attr) { return $attr['unit_price'] * $attr['quantity']; }
        ]);

        $order->update(['total_amount' => $items->sum('total_price')]);

        event(new OrderCreated($order));

        $this->info('Order simulated successfully!');

        return Command::SUCCESS;
    }

}
