<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCosting;
use App\Models\StoneType;
use App\Models\OxoFactor;
use App\Services\CostingCalculationService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CostingController extends Controller
{
    public function __construct(
        private CostingCalculationService $costingService
    ) {}

    public function index(): Response
    {
        return Inertia::render('Costing/Index', [
            'rates' => $this->costingService->getCurrentRates(),
        ]);
    }

    public function calculator(): Response
    {
        return Inertia::render('Costing/Calculator', [
            'rates' => $this->costingService->getCurrentRates(),
            'stoneTypes' => StoneType::active()->get(),
            'oxoFactors' => OxoFactor::active()->get(),
        ]);
    }

    public function calculate(Product $product): Response
    {
        $calculation = $this->costingService->calculateProductCost($product);

        return Inertia::render('Costing/Calculate', [
            'product' => $product,
            'calculation' => $calculation,
            'rates' => $this->costingService->getCurrentRates(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'weight' => 'required|numeric|min:0',
            'mino_count' => 'integer|min:0',
            'stone_count' => 'integer|min:0',
            'chips_count' => 'integer|min:0',
            'oxo_factor' => 'numeric|min:0|max:1',
            'stone_type' => 'in:k,S,G,A',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Update product with new values if changed
        $product->update([
            'weight' => $validated['weight'],
            'mino_count' => $validated['mino_count'] ?? 0,
            'stone_count' => $validated['stone_count'] ?? 0,
            'chips_count' => $validated['chips_count'] ?? 0,
            'oxo_factor' => $validated['oxo_factor'] ?? 0,
            'stone_type' => $validated['stone_type'] ?? 'k',
        ]);

        $calculation = $this->costingService->calculateProductCost($product);
        $this->costingService->saveCosting($product, $calculation, auth()->id());

        return redirect()->route('costing.history')
            ->with('success', 'Costing saved successfully. Final Price: ₹' . number_format($calculation['final_price'], 2));
    }

    public function quickCalculate(Request $request)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0',
            'mino_count' => 'integer|min:0',
            'stone_count' => 'integer|min:0',
            'chips_count' => 'integer|min:0',
            'oxo_factor' => 'numeric|min:0|max:1',
            'stone_type' => 'in:k,S,G,A',
        ]);

        $calculation = $this->costingService->calculateFromInputs($validated);

        return response()->json($calculation);
    }

    public function history(Request $request): Response
    {
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        $costings = ProductCosting::with('product')
            ->when($request->product_number, function ($query, $productNumber) {
                $query->whereHas('product', fn($q) => $q->where('product_number', 'like', "%{$productNumber}%"));
            })
            ->when($request->date_from, fn($q, $date) => $q->whereDate('costing_date', '>=', $date))
            ->when($request->date_to, fn($q, $date) => $q->whereDate('costing_date', '<=', $date))
            ->orderBy($sortField, $sortOrder)
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Costing/History', [
            'costings' => $costings,
            'tableFilters' => $request->only(['sort', 'order']),
            'filters' => $request->only(['product_number', 'date_from', 'date_to']),
        ]);
    }

    public function bulk(): Response
    {
        return Inertia::render('Costing/Bulk', [
            'rates' => $this->costingService->getCurrentRates(),
        ]);
    }

    public function bulkCalculate(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $results = [];
        foreach ($validated['product_ids'] as $productId) {
            $product = Product::find($productId);
            $calculation = $this->costingService->calculateProductCost($product);
            $this->costingService->saveCosting($product, $calculation, auth()->id());
            $results[] = [
                'product' => $product,
                'calculation' => $calculation,
            ];
        }

        return response()->json([
            'message' => count($results) . ' products calculated successfully',
            'results' => $results,
        ]);
    }
}
