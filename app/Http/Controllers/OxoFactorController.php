<?php

namespace App\Http\Controllers;

use App\Models\OxoFactor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OxoFactorController extends Controller
{
    public function index(Request $request): Response
    {
        $sortField = $request->get('sort', 'value');
        $sortOrder = $request->get('order', 'asc');

        return Inertia::render('Masters/OxoFactors/Index', [
            'oxoFactors' => OxoFactor::orderBy($sortField, $sortOrder)->paginate(25)->withQueryString(),
            'filters' => $request->only(['sort', 'order']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Masters/OxoFactors/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|integer|unique:oxo_factors,value',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        OxoFactor::create($validated);

        return redirect()->route('oxo-factors.index')
            ->with('success', 'OXO factor created successfully.');
    }

    public function edit(OxoFactor $oxoFactor): Response
    {
        return Inertia::render('Masters/OxoFactors/Form', [
            'oxoFactor' => $oxoFactor,
        ]);
    }

    public function update(Request $request, OxoFactor $oxoFactor)
    {
        $validated = $request->validate([
            'value' => 'required|integer|unique:oxo_factors,value,' . $oxoFactor->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $oxoFactor->update($validated);

        return redirect()->route('oxo-factors.index')
            ->with('success', 'OXO factor updated successfully.');
    }

    public function destroy(OxoFactor $oxoFactor)
    {
        $oxoFactor->delete();

        return redirect()->route('oxo-factors.index')
            ->with('success', 'OXO factor deleted successfully.');
    }
}
