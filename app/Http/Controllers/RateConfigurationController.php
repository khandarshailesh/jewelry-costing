<?php

namespace App\Http\Controllers;

use App\Models\RateConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class RateConfigurationController extends Controller
{
    public function index(Request $request): Response
    {
        $sortField = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        $query = RateConfiguration::query();

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        // Type filter
        if ($request->rate_type) {
            $query->where('rate_type', $request->rate_type);
        }

        // Status filter
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        return Inertia::render('Rates/Index', [
            'rates' => $query->orderBy($sortField, $sortOrder)->paginate(25)->withQueryString(),
            'filters' => $request->only(['sort', 'order', 'search', 'rate_type', 'is_active']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Rates/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:rate_configurations',
            'rate_value' => 'required|numeric',
            'rate_type' => 'required|in:multiplier,fixed,percentage',
            'description' => 'nullable|string',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        RateConfiguration::create($validated);
        Cache::forget('all_active_rates');

        return redirect()->route('rates.index')
            ->with('success', 'Rate configuration created successfully.');
    }

    public function edit(RateConfiguration $rate): Response
    {
        return Inertia::render('Rates/Edit', [
            'rate' => $rate,
        ]);
    }

    public function update(Request $request, RateConfiguration $rate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:rate_configurations,code,' . $rate->id,
            'rate_value' => 'required|numeric',
            'rate_type' => 'required|in:multiplier,fixed,percentage',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        $rate->update($validated);
        Cache::forget('all_active_rates');
        Cache::forget("rate_{$rate->code}");

        return redirect()->route('rates.index')
            ->with('success', 'Rate configuration updated successfully.');
    }

    public function destroy(RateConfiguration $rate)
    {
        Cache::forget("rate_{$rate->code}");
        Cache::forget('all_active_rates');
        $rate->delete();

        return redirect()->route('rates.index')
            ->with('success', 'Rate configuration deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'rates' => 'required|array',
            'rates.*.id' => 'required|exists:rate_configurations,id',
            'rates.*.rate_value' => 'required|numeric',
        ]);

        foreach ($validated['rates'] as $rateData) {
            $rate = RateConfiguration::find($rateData['id']);
            $rate->update(['rate_value' => $rateData['rate_value']]);
            Cache::forget("rate_{$rate->code}");
        }

        Cache::forget('all_active_rates');

        return redirect()->route('rates.index')
            ->with('success', 'Rates updated successfully.');
    }
}
