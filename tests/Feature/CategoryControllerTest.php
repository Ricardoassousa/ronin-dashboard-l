<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase; // Run each test in memory and rollback automatically

    protected User $user;

    /**
     * Set up the test environment.
     *
     * Creates an authenticated admin user to access admin routes.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a user with admin role
        $this->user = User::factory()->create([
            'role' => 'admin', // Ensure the user passes the 'can:admin' middleware
        ]);

        // Authenticate as the admin user
        $this->actingAs($this->user);
    }

    /**
     * Test that the index page displays the list of categories.
     *
     * @return void
     */
    public function test_index_displays_categories_list(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories');
    }

    /**
     * Test that the create page displays the category creation form.
     *
     * @return void
     */
    public function test_create_displays_category_form(): void
    {
        $response = $this->get(route('admin.categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.categories.create');
    }

    /**
     * Test storing a new category in the database.
     *
     * @return void
     */
    public function test_store_creates_new_category(): void
    {
        $response = $this->post(route('admin.categories.store'), [
            'name' => 'New Category',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category created successfully.');
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    // /**
    //  * Test that storing a category without a name fails validation.
    //  *
    //  * @return void
    //  */
    // public function test_store_fails_if_name_is_missing(): void
    // {
    //     $response = $this->post(route('admin.categories.store'), []);

    //     $response->assertSessionHasErrors('name');
    // }

    /**
     * Test updating an existing category's name.
     *
     * @return void
     */
    public function test_update_modifies_existing_category(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->put(route('admin.categories.update', $category), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category updated successfully.');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Name']);
    }

    /**
     * Test deleting a category that has no products.
     *
     * @return void
     */
    public function test_destroy_deletes_category_without_products(): void
    {
        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success', 'Category deleted successfully.');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Test that deleting a category with products fails.
     *
     * @return void
     */
    public function test_destroy_fails_if_category_has_products(): void
    {
        $category = Category::factory()->create();
        $category->products()->createMany([
            ['name' => 'Product 1', 'price' => 10.0, 'is_active' => true],
        ]);

        $response = $this->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error', 'Cannot delete category with products.');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}