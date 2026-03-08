<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        try {
            Log::info('Accessing product index', [
                'user_id' => auth()->id(),
                'filters' => [
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                ]
            ]);

            $products = Product::query()
                ->when($request->name, fn($q) => $q->where('name', 'like', "%{$request->name}%"))
                ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
                ->paginate(15);

            return view('admin.products.index', compact('products'));

        } catch (Exception $e) {
            Log::error('Error accessing product index', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to retrieve products.');
        }
    }

    /**
     * Show the form for creating a new product.
     *
     * @return View
     */
    public function create(): View
    {
        try {
            Log::info('Accessing product create page', [
                'user_id' => auth()->id()
            ]);

            $categories = Category::all();
            return view('admin.products.create', compact('categories'));

        } catch (Exception $e) {
            Log::error('Error accessing product create page', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to access product creation page.');
        }
    }

    /**
     * Store a newly created product in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'category_id' => 'required|exists:categories,id',
            ]);

            $product = Product::create($data);

            activity('product')
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->withProperties($data)
                ->log('Product created');

            return redirect()->route('admin.products.index')
                             ->with('success', 'Product created successfully.');

        } catch (Exception $e) {
            Log::error('Error creating product', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to create product.');
        }
    }

    /**
     * Display the specified product.
     *
     * @param Product $product
     * @return View|RedirectResponse
     */
    public function show(Product $product)
    {
        try {
            Log::info('Viewing product details', [
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);

            return view('admin.products.show', compact('product'));

        } catch (Exception $e) {
            Log::error('Error viewing product details', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to view product.');
        }
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param Product $product
     * @return View|RedirectResponse
     */
    public function edit(Product $product)
    {
        try {
            Log::info('Accessing product edit page', [
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);

            $categories = Category::all();
            return view('admin.products.edit', compact('product', 'categories'));

        } catch (Exception $e) {
            Log::error('Error accessing product edit page', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to access product edit page.');
        }
    }

    /**
     * Update the specified product in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'category_id' => 'required|exists:categories,id',
            ]);

            $oldData = $product->only(['name', 'price', 'stock', 'category_id']);
            $product->update($data);

            activity('product')
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old' => $oldData,
                    'new' => $data
                ])
                ->log('Product updated');

            return redirect()->route('admin.products.index')
                             ->with('success', 'Product updated successfully.');

        } catch (Exception $e) {
            Log::error('Error updating product', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to update product.');
        }
    }

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $productId = $product->id;
            $productName = $product->name;

            $product->delete();

            activity('product')
                ->causedBy(auth()->user())
                ->withProperties([
                    'product_id' => $productId,
                    'product_name' => $productName
                ])
                ->log('Product deleted');

            return back()->with('success', 'Product deleted successfully.');

        } catch (Exception $e) {
            Log::error('Error deleting product', [
                'user_id' => auth()->id(),
                'product_id' => $product->id ?? null,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Unable to delete product.');
        }
    }
}