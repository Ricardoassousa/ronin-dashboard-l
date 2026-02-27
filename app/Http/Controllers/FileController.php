<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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

        return view('files.index', compact('files'));
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

        return view('files.create');
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
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        $file = File::create([
            'filename' => $uploadedFile->getClientOriginalName(),
            'path' => $path
        ]);

        Log::info('File uploaded successfully.', [
            'user_id' => auth()->id(),
            'file_id' => $file->id,
            'filename' => $file->filename,
            'path' => $file->path,
        ]);

        return redirect()
            ->back()
            ->with('success', 'File uploaded successfully.');
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
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
            Log::info('Physical file deleted from storage.', [
                'user_id' => auth()->id(),
                'file_id' => $file->id,
                'path' => $file->path,
            ]);
        } else {
            Log::warning('Attempted to delete file that does not exist.', [
                'user_id' => auth()->id(),
                'file_id' => $file->id,
                'path' => $file->path,
            ]);
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
    }

}