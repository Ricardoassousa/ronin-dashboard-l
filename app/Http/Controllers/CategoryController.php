<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        Log::info('Accessing category index', [
            'user_id' => auth()->id(),
            'filters' => $request->only('name')
        ]);

        $query = Category::query();

        // Filter by name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return View
     */
    public function create(): View
    {
        Log::info('Accessing category create page', [
            'user_id' => auth()->id()
        ]);

        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($request->only('name', 'description'));

        Log::info('Category created successfully', [
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'category_name' => $category->name
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     *
     * @param Category $category
     * @return View
     */
    public function show(Category $category): View
    {
        Log::info('Viewing category details', [
            'user_id' => auth()->id(),
            'category_id' => $category->id
        ]);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param Category $category
     * @return View
     */
    public function edit(Category $category): View
    {
        Log::info('Accessing category edit page', [
            'user_id' => auth()->id(),
            'category_id' => $category->id
        ]);

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $oldData = $category->only('name', 'description');

        $category->update($request->only('name', 'description'));

        Log::info('Category updated successfully', [
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'old_data' => $oldData,
            'new_data' => $category->fresh()->only('name', 'description')
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Optional: prevent deletion if category has products
        if ($category->products()->count()) {

            Log::warning('Attempted to delete category with products', [
                'user_id' => auth()->id(),
                'category_id' => $category->id
            ]);

            return redirect()->route('admin.categories.index')
                             ->with('error', 'Cannot delete category with products.');
        }

        $categoryId = $category->id;
        $categoryName = $category->name;

        $category->delete();

        Log::info('Category deleted successfully', [
            'user_id' => auth()->id(),
            'category_id' => $categoryId,
            'category_name' => $categoryName
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category deleted successfully.');
    }

}