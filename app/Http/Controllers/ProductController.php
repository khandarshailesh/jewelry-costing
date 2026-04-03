<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StoneType;
use App\Models\OxoFactor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $sortField = $request->get('sort', 'product_number');
        $sortOrder = $request->get('order', 'asc');

        $products = Product::with(['category', 'latestCosting'])
            ->search($request->search)
            ->when($request->category, fn($q, $cat) => $q->where('category_id', $cat))
            ->when($request->stone_type, fn($q, $type) => $q->where('stone_type', $type))
            ->when($request->has('active'), fn($q) => $q->where('is_active', $request->boolean('active')))
            ->orderBy($sortField, $sortOrder)
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => Category::all(),
            'stoneTypes' => StoneType::active()->get(),
            'filters' => $request->only(['search', 'category', 'stone_type', 'active', 'sort', 'order']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Products/Form', [
            'categories' => Category::all(),
            'stoneTypes' => StoneType::active()->get(),
            'oxoFactors' => OxoFactor::active()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_number' => 'required|string|max:20|unique:products',
            'name' => 'nullable|string|max:255',
            'weight' => 'required|numeric|min:0',
            'mino_count' => 'integer|min:0',
            'stone_count' => 'integer|min:0',
            'chips_count' => 'integer|min:0',
            'oxo_factor' => 'numeric|min:0|max:1',
            'stone_type' => 'in:k,S,G,A',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): Response
    {
        return Inertia::render('Products/Show', [
            'product' => $product->load(['category', 'costings' => fn($q) => $q->latest()->take(20)]),
        ]);
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Products/Form', [
            'product' => $product,
            'categories' => Category::all(),
            'stoneTypes' => StoneType::active()->get(),
            'oxoFactors' => OxoFactor::active()->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_number' => 'required|string|max:20|unique:products,product_number,' . $product->id,
            'name' => 'nullable|string|max:255',
            'weight' => 'required|numeric|min:0',
            'mino_count' => 'integer|min:0',
            'stone_count' => 'integer|min:0',
            'chips_count' => 'integer|min:0',
            'oxo_factor' => 'numeric|min:0|max:1',
            'stone_type' => 'in:k,S,G,A',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
