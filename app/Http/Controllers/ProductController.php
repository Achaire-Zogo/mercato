<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Apply stock filter
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->lowStock();
            } elseif ($request->stock === 'out') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        $products = $query->with('category')
                         ->latest()
                         ->paginate(12)
                         ->appends(request()->query());

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|unique:products',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:10240', // 10MB max
            'expiration_date' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = 'image_' . time() . '.' . $extension;
            $file->move(public_path('products_mercato'), $filename);
            $validated['image_path'] = $filename;
        }


        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'matricule' => ['required', 'string', Rule::unique('products')->ignore($product)],
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'alert_threshold' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:10240',
            'expiration_date' => 'nullable|date',
        ]);



        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                $path = public_path('products_mercato/' . $product->image_path);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = 'image_' . time() . '.' . $extension;
            $file->move(public_path('products_mercato'), $filename);
            $validated['image_path'] = $filename;
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {

            if ($product->image_path) {
                $path = public_path('products_mercato/' . $product->image_path);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        


        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment' => 'required|integer',
        ]);

        $newQuantity = $product->stock_quantity + $validated['adjustment'];

        if ($newQuantity < 0) {
            return back()->with('error', 'Stock quantity cannot be negative.');
        }

        $product->update(['stock_quantity' => $newQuantity]);

        return back()->with('success', 'Stock updated successfully.');
    }
}
