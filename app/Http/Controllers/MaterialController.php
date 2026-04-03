<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaterialController extends Controller
{
    public function index(Request $request): Response
    {
        $sortField = $request->get('sort', 'material_number');
        $sortOrder = $request->get('order', 'asc');

        $materials = Material::with('category')
            ->search($request->search)
            ->when($request->category_id, fn($q, $cat) => $q->where('category_id', $cat))
            ->when($request->has('is_active'), fn($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy($sortField, $sortOrder)
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Materials/Index', [
            'materials' => $materials,
            'categories' => \App\Models\Category::all(),
            'filters' => $request->only(['search', 'category_id', 'is_active', 'sort', 'order']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Materials/Form', [
            'categories' => \App\Models\Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_number' => 'required|string|max:20|unique:materials',
            'name' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'unit' => 'string|max:20',
            'category' => 'nullable|string|max:50',
        ]);

        Material::create($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material created successfully.');
    }

    public function edit(Material $material): Response
    {
        return Inertia::render('Materials/Form', [
            'material' => $material,
            'categories' => \App\Models\Category::all(),
        ]);
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'material_number' => 'required|string|max:20|unique:materials,material_number,' . $material->id,
            'name' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'unit' => 'string|max:20',
            'category' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $material->update($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material deleted successfully.');
    }
}
