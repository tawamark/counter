<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
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
            'shortageItems' => InventoryCountItem::whereHas('inventoryCount', fn ($query) => $query->where('company_id', $companyId))
                ->where('difference', '<', 0)
                ->whereNotNull('counted_quantity')
                ->count(),
            'surplusItems' => InventoryCountItem::whereHas('inventoryCount', fn ($query) => $query->where('company_id', $companyId))
                ->where('difference', '>', 0)
                ->whereNotNull('counted_quantity')
                ->count(),
            'recentInventoryCounts' => InventoryCount::with('creator')
                ->where('company_id', $companyId)
                ->latest()
                ->limit(5)
                ->get(),
            'recentMovements' => StockMovement::with(['product', 'user'])
                ->where('company_id', $companyId)
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
