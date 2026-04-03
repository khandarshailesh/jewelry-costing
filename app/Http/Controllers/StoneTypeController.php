<?php

namespace App\Http\Controllers;

use App\Models\StoneType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoneTypeController extends Controller
{
    public function index(Request $request): Response
    {
        $sortField = $request->get('sort', 'code');
        $sortOrder = $request->get('order', 'asc');

        return Inertia::render('Masters/StoneTypes/Index', [
            'stoneTypes' => StoneType::orderBy($sortField, $sortOrder)->paginate(25)->withQueryString(),
            'filters' => $request->only(['sort', 'order']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Masters/StoneTypes/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:stone_types,code',
            'name' => 'required|string|max:255',
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        StoneType::create($validated);

        return redirect()->route('stone-types.index')
            ->with('success', 'Stone type created successfully.');
    }

    public function edit(StoneType $stoneType): Response
    {
        return Inertia::render('Masters/StoneTypes/Form', [
            'stoneType' => $stoneType,
        ]);
    }

    public function update(Request $request, StoneType $stoneType)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:stone_types,code,' . $stoneType->id,
            'name' => 'required|string|max:255',
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $stoneType->update($validated);

        return redirect()->route('stone-types.index')
            ->with('success', 'Stone type updated successfully.');
    }

    public function destroy(StoneType $stoneType)
    {
        $stoneType->delete();

        return redirect()->route('stone-types.index')
            ->with('success', 'Stone type deleted successfully.');
    }
}
