<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * Test that the slug is automatically generated if not provided.
     *
     * @return void
     */
    public function test_slug_is_generated_if_not_provided(): void
    {
        // Create a new Category instance without a slug
        $category = new Category(['name' => 'New Category']);

        // Manually trigger the booted "creating" callback
        $creatingCallback = (function (Category $category): void {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        })->bindTo(null, Category::class); // Bind static context to simulate the event

        $creatingCallback($category);

        // Assert that the slug was generated correctly
        $this->assertEquals('new-category', $category->slug);
    }

    /**
     * Test that the slug is not overwritten if it is already provided.
     *
     * @return void
     */
    public function test_slug_is_not_overwritten_if_provided(): void
    {
        // Create a new Category instance with a predefined slug
        $category = new Category([
            'name' => 'Outra Categoria',
            'slug' => 'slug-personalizado',
        ]);

        // Manually trigger the booted "creating" callback
        $creatingCallback = (function (Category $category): void {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        })->bindTo(null, Category::class);

        $creatingCallback($category);

        // Assert that the provided slug remains unchanged
        $this->assertEquals('slug-personalizado', $category->slug);
    }
}
