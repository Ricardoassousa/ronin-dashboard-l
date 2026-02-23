<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['order', 'user', 'product']);

        $productNames = [
            'Wireless Headphones',
            'Smartphone X',
            'Laptop Pro',
            'Gaming Mouse',
            'Fitness Tracker',
            '4K Monitor',
            'Bluetooth Speaker',
            'Smartwatch Series 5',
            'Ergonomic Chair',
            'Portable Charger'
        ];

        $description = match($type) {
            'order' => "New order #{$this->faker->numberBetween(1000, 1050)} created",
            'product' => "Product '" . $this->faker->randomElement($productNames) . "' added",
            'user' => $this->faker->randomElement([
                "New user " . $this->faker->name() . " registered",
                "User " . $this->faker->name() . " updated profile",
                "User " . $this->faker->name() . " changed password",
                "User " . $this->faker->name() . " deactivated account",
                "User " . $this->faker->name() . " upgraded subscription"
            ]),
        };

        return [
            'type' => $type,
            'description' => $description,
            'user_id' => User::inRandomOrder()->first()?->id,
            'created_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'updated_at' => now(),
        ];
    }

}
