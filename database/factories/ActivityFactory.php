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
        // Added 'category' and 'file' types
        $type = $this->faker->randomElement(['category', 'customer', 'file', 'order', 'product']);

        $productNames = [
            'Wireless Headphones', 'Smartphone X', 'Laptop Pro', 'Gaming Mouse',
            'Fitness Tracker', '4K Monitor', 'Bluetooth Speaker',
            'Smartwatch Series 5', 'Ergonomic Chair', 'Portable Charger'
        ];

        $categoryNames = ['Electronics', 'Home & Garden', 'Fashion', 'Sports', 'Health', 'Toys'];
        $fileExtensions = ['pdf', 'jpg', 'png', 'xlsx', 'zip'];

        $description = match($type) {
            'order' => "New order #{$this->faker->numberBetween(1000, 1050)} created",
            'product' => "Product '" . $this->faker->randomElement($productNames) . "' added",
            'customer' => "Customer '" . $this->faker->name() . "' registered",
            'category' => "Category '" . $this->faker->randomElement($categoryNames) . "' updated",
            'file' => "File 'report_" . $this->faker->word() . "." . $this->faker->randomElement($fileExtensions) . "' uploaded",
            default => "System activity logged",
        };

        return [
            'type' => $type,
            'description' => $description,
            'user_id' => in_array($type, ['category', 'file', 'product'])
                ? (User::inRandomOrder()->first()?->id ?? User::factory())
                : null,
            'created_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'updated_at' => now(),
        ];
    }
}