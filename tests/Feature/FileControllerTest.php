<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    /**
     * Set up the test environment.
     *
     * Creates an authenticated admin user and prepares fake storage.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Fake the private disk
        Storage::fake('private');

        // Create an admin user
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($this->adminUser);
    }

    /**
     * Test that the index page displays the files list.
     *
     * @return void
     */
    public function test_index_displays_files_list(): void
    {
        File::factory()->count(3)->create([]);

        $response = $this->get(route('admin.files.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.files.index');
        $response->assertViewHas('files');
    }

    /**
     * Test that the create page displays the file upload form.
     *
     * @return void
     */
    public function test_create_displays_file_form(): void
    {
        $response = $this->get(route('admin.files.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.files.create');
    }

    /**
     * Test storing a valid uploaded file.
     *
     * @return void
     */
    public function test_store_uploads_file_successfully(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->post(route('admin.files.store'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'File uploaded successfully.');

        // Assert the file exists in the fake storage
        Storage::disk('private')->assertExists('uploads/' . $file->hashName());

        // Assert database contains the file record
        $this->assertDatabaseHas('files', [
            'filename' => 'document.pdf',
        ]);
    }

    // /**
    //  * Test storing a file with invalid type fails validation.
    //  *
    //  * @return void
    //  */
    // public function test_store_fails_with_invalid_file_type(): void
    // {
    //     $file = UploadedFile::fake()->create('file.exe', 100);

    //     $response = $this->post(route('admin.files.store'), [
    //         'file' => $file,
    //     ]);

    //     $response->assertSessionHasErrors('file');
    // }

    /**
     * Test deleting a file successfully.
     *
     * @return void
     */
    public function test_destroy_deletes_file(): void
    {
        $file = File::factory()->create([
            'path' => 'uploads/testfile.pdf',
        ]);

        // Put a fake file into the storage
        Storage::disk('private')->put($file->path, 'dummy content');

        $response = $this->delete(route('admin.files.destroy', $file));

        $response->assertRedirect(route('admin.files.index'));
        $response->assertSessionHas('success', 'File deleted successfully.');

        Storage::disk('private')->assertMissing($file->path);
        $this->assertDatabaseMissing('files', ['id' => $file->id]);
    }

    /**
     * Test downloading a file.
     *
     * @return void
     */
    public function test_download_returns_file(): void
    {
        $file = File::factory()->create([
            'path' => 'uploads/sample.pdf',
            'filename' => 'sample.pdf'
        ]);

        Storage::disk('private')->put($file->path, 'dummy content');

        $response = $this->get(route('admin.files.download', $file));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition');
    }

    /**
     * Test previewing a file.
     *
     * @return void
     */
    public function test_preview_returns_file_content(): void
    {
        $file = File::factory()->create([
            'path' => 'uploads/sample_preview.pdf',
            'filename' => 'sample_preview.pdf'
        ]);

        Storage::disk('private')->put($file->path, 'dummy content');

        $response = $this->get(route('admin.files.preview', $file));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type');
        $response->assertSee('dummy content');
    }

    // /**
    //  * Test that preview of a non-existent file returns an error.
    //  *
    //  * @return void
    //  */
    // public function test_preview_returns_error_if_file_not_found(): void
    // {
    //     $file = File::factory()->create([
    //         'path' => 'uploads/nonexistent.pdf',
    //         'filename' => 'nonexistent.pdf'
    //     ]);

    //     $response = $this->get(route('admin.files.preview', $file));

    //     $response->assertRedirect();
    //     $response->assertSessionHas('error', 'File not found.');
    // }
}