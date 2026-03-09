<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'cancelled']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'boleto']),
            'shipping_address' => $this->faker->address(),
            'billing_address' => $this->faker->address(),
            'placed_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
