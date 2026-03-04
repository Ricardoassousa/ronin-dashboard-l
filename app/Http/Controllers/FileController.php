<?php

namespace App\Http\Controllers;

use App\Models\File;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Display a listing of uploaded files.
     *
     * @return View
     */
    public function index(): View
    {
        $files = File::latest()->paginate(10);

        Log::info('Admin accessed file index.', [
            'user_id' => auth()->id(),
            'total_files' => $files->total(),
        ]);

        return view('admin.files.index', compact('files'));
    }

    /**
     * Show the form for creating a new file upload.
     *
     * @return View
     */
    public function create(): View
    {
        Log::info('Admin accessed file upload form.', [
            'user_id' => auth()->id(),
        ]);

        return view('admin.files.create');
    }

    /**
     * Store a newly uploaded file in storage.
     *
     * Validates the uploaded file, stores it securely,
     * and saves its metadata in the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->authorize('create', File::class);

            $request->validate([
                'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);

            $uploadedFile = $request->file('file');
            $path = $uploadedFile->store('uploads', 'private');

            $file = File::create([
                'filename' => $uploadedFile->getClientOriginalName(),
                'path' => $path,
                'user_id' => auth()->id(),
            ]);

            activity('file')
                ->performedOn($file)
                ->causedBy(auth()->user())
                ->withProperties([
                    'filename' => $file->filename,
                    'path' => $file->path,
                    'user_id' => auth()->id(),
                ])
                ->log('File uploaded');

            return redirect()
                ->back()
                ->with('success', 'File uploaded successfully.');

        } catch (Exception $e) {

            activity('file-error')
                ->causedBy(auth()->user())
                ->withProperties([
                    'error' => $e->getMessage()
                ])
                ->log('File upload failed');

            return redirect()
                ->back()
                ->with('error', 'There was a problem uploading the file.');
        }
    }

    /**
     * Remove the specified file from storage and database.
     *
     * Deletes the physical file from the public disk and
     * removes its corresponding database record.
     *
     * @param File $file
     * @return RedirectResponse
     */
    public function destroy(File $file): RedirectResponse
    {
        try {
            $this->authorize('delete', $file);

            $fileId = $file->id;
            $filename = $file->filename;
            $path = $file->path;

            if (Storage::disk('private')->exists($file->path)) {
                Storage::disk('private')->delete($file->path);

                activity('file')
                    ->performedOn($file)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'file_id' => $fileId,
                        'filename' => $filename,
                    ])
                    ->log('File deleted from storage');
            } else {
                activity('file-warning')
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'file_id' => $fileId,
                        'path' => $path,
                    ])
                    ->log('Attempted to delete non-existent file');
            }

            $file->delete();

            Log::info('File record deleted from database.', [
                'user_id' => auth()->id(),
                'file_id' => $file->id,
                'filename' => $file->filename,
            ]);

            return redirect()
                ->route('admin.files.index')
                ->with('success', 'File deleted successfully.');

        } catch (Exception $e) {

            Log::error('File deletion failed.', [
                'user_id' => auth()->id(),
                'file_id' => $file->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'There was a problem deleting the file.');
        }
    }

    /**
     * Download a file from the private storage.
     *
     * Only authorized users (admins) can download files.
     * Logs both successful and failed attempts.
     *
     * @param File $file
     * @return BinaryFileResponse|RedirectResponse
     */
    public function download(File $file)
    {
        try {
            $this->authorize('view', $file);

            activity('file-download')
                ->performedOn($file)
                ->causedBy(auth()->user())
                ->withProperties([
                    'filename' => $file->filename,
                    'path' => $file->path
                ])
                ->log('File download initiated');

            return Storage::disk('private')->download($file->path, $file->filename);

        } catch (AuthorizationException $e) {

            activity('file-download-warning')
                ->causedBy(auth()->user())
                ->withProperties([
                    'file_id' => $file->id,
                    'filename' => $file->filename
                ])
                ->log('Unauthorized file download attempt');

            return redirect()->back()->with('error', 'You are not authorized to download this file.');

        } catch (Exception $e) {

            Log::error('File download failed.', [
                'user_id' => auth()->id(),
                'file_id' => $file->id,
                'filename' => $file->filename,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'There was a problem downloading the file.');
        }
    }

    /**
     * Preview a file stored on the private disk.
     *
     * This method allows only administrators to preview images or files stored
     * in the private disk. It returns the file content with the correct MIME type
     * and inline disposition so it can be displayed in the browser.
     *
     * @param File $file
     * @return Response|RedirectResponse
     */
    public function preview(File $file)
    {
        try {
            // Get file content and MIME type from private storage
            $content = Storage::disk('private')->get($file->path);
            $mime = Storage::disk('private')->mimeType($file->path);

            return response($content, 200)
                ->header('Content-Type', $mime)
                ->header('Content-Disposition', 'inline; filename="' . $file->filename . '"');

        } catch (FileNotFoundException $e) {

            // Log file not found error
            Log::error('File not found for preview.', [
                'file_id' => $file->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'File not found.');

        } catch (Exception $e) {

            // Log any other errors
            Log::error('Error previewing file.', [
                'file_id' => $file->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Unable to preview the file.');
        }
    }

}