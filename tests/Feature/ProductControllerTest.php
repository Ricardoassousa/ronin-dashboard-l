<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    /**
     * Set up the test environment.
     *
     * Creates an authenticated admin user and a category for products.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->user = User::factory()->create([
            'role' => 'admin', // Adjust according to your roles setup
        ]);

        $this->actingAs($this->user);

        // Create a category to associate with products
        $this->category = Category::factory()->create();
    }

    /**
     * Test that the index page displays a list of products.
     *
     * @return void
     */
    public function test_index_displays_products_list(): void
    {
        Product::factory()->count(3)->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
        $response->assertViewHas('products');
    }

    /**
     * Test that the create page displays the product creation form.
     *
     * @return void
     */
    public function test_create_displays_product_form(): void
    {
        $response = $this->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
        $response->assertViewHas('categories');
    }

    /**
     * Test storing a new product in the database.
     *
     * @return void
     */
    public function test_store_creates_new_product(): void
    {
        $response = $this->post(route('admin.products.store'), [
            'name' => 'Test Product',
            'price' => 50.5,
            'stock' => 10,
            'category_id' => $this->category->id,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product created successfully.');
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    // /**
    //  * Test that storing a product with missing required fields fails validation.
    //  *
    //  * @return void
    //  */
    // public function test_store_fails_if_required_fields_missing(): void
    // {
    //     $response = $this->post(route('admin.products.store'), []);

    //     $response->assertSessionHasErrors(['name', 'price', 'stock', 'category_id']);
    // }

    /**
     * Test that the edit page displays the product edit form.
     *
     * @return void
     */
    public function test_edit_displays_product_edit_form(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHasAll(['product', 'categories']);
    }

    /**
     * Test updating an existing product.
     *
     * @return void
     */
    public function test_update_modifies_existing_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'price' => 20,
            'stock' => 5,
            'category_id' => $this->category->id
        ]);

        $response = $this->put(route('admin.products.update', $product), [
            'name' => 'Updated Name',
            'price' => 25,
            'stock' => 8,
            'category_id' => $this->category->id
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product updated successfully.');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name']);
    }

    /**
     * Test displaying a single product's details.
     *
     * @return void
     */
    public function test_show_displays_product_details(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->get(route('admin.products.show', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.show');
        $response->assertViewHas('product');
    }

    /**
     * Test deleting a product.
     *
     * @return void
     */
    public function test_destroy_deletes_product(): void
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id
        ]);

        $response = $this->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(); // returns back()
        $response->assertSessionHas('success', 'Product deleted successfully.');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}