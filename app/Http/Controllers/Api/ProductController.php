<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        $products = $query->orderBy('sort_order')->orderBy('name')->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'sku' => 'nullable|string|unique:products',
            'modifiers' => 'nullable|array',
            'is_available' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load('category'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:products,slug,' . $id,
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'image' => 'nullable|string',
            'sku' => 'nullable|string|unique:products,sku,' . $id,
            'modifiers' => 'nullable|array',
            'is_available' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $product->update($validated);

        return response()->json($product->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Upload product image
     */
    public function uploadImage(Request $request, string $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // Delete old image if exists
        if ($product->image_url) {
            $oldPath = str_replace('/storage/', 'public/', $product->image_url);
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }

        // Store new image
        $path = $request->file('image')->store(
            'public/products/' . now()->format('Y/m'),
            'local'
        );

        $url = Storage::url($path);

        $product->image_url = $url;
        $product->save();

        return response()->json([
            'message' => 'Image uploaded successfully',
            'image_url' => $url,
        ]);
    }

    /**
     * Delete product image
     */
    public function deleteImage(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_url) {
            $oldPath = str_replace('/storage/', 'public/', $product->image_url);
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }

        $product->image_url = null;
        $product->save();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}
