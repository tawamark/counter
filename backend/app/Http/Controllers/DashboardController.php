<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $companyId = auth()->user()->company_id;

        return view('dashboard.index', [
            'totalProducts' => Product::where('company_id', $companyId)->count(),
            'totalCategories' => Category::where('company_id', $companyId)->count(),
            'totalSuppliers' => Supplier::where('company_id', $companyId)->count(),
            'openInventoryCounts' => InventoryCount::where('company_id', $companyId)
                ->whereIn('status', ['open', 'in_progress'])
                ->count(),
            'recentMovements' => StockMovement::with(['product', 'user'])
                ->where('company_id', $companyId)
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
