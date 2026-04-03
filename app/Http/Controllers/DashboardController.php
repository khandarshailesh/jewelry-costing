<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCosting;
use App\Models\Material;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_products' => Product::count(),
                'active_products' => Product::active()->count(),
                'total_materials' => Material::count(),
                'costings_today' => ProductCosting::whereDate('costing_date', today())->count(),
                'costings_this_month' => ProductCosting::whereMonth('costing_date', now()->month)->count(),
            ],
            'recent_costings' => ProductCosting::with('product')
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}
