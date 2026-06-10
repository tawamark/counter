<?php

namespace App\Http\Controllers;

use App\Models\InventoryCount;
use App\Models\Product;
use App\Services\InventoryCountService;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventoryCountController extends Controller
{
    public function index(): View
    {
        $counts = InventoryCount::with('creator')
            ->withCount('items')
            ->where('company_id', auth()->user()->company_id)
            ->latest()
            ->paginate(10);

        return view('inventory-counts.index', [
            'counts' => $counts,
        ]);
    }

    public function create(): View
    {
        return view('inventory-counts.create', [
            'products' => $this->products(),
        ]);
    }

    public function store(Request $request, InventoryCountService $service): RedirectResponse
    {
        $companyId = auth()->user()->company_id;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', Rule::exists('products', 'id')->where('company_id', $companyId)],
        ]);

        $count = $service->create(auth()->user(), $data['title'], $data['product_ids']);

        return redirect()
            ->route('inventory-counts.show', $count)
            ->with('status', 'Contagem criada com sucesso.');
    }

    public function show(InventoryCount $inventoryCount): View
    {
        abort_unless($inventoryCount->company_id === auth()->user()->company_id, 404);

        $inventoryCount->load(['creator', 'items.product', 'items.counter', 'adjustmentMovements.product', 'adjustmentMovements.user']);

        return view('inventory-counts.show', [
            'count' => $inventoryCount,
        ]);
    }

    public function updateItems(Request $request, InventoryCount $inventoryCount, InventoryCountService $service): RedirectResponse
    {
        abort_unless($inventoryCount->company_id === auth()->user()->company_id, 404);

        $itemIds = $inventoryCount->items()->pluck('id')->all();

        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', Rule::in($itemIds)],
            'items.*.counted_quantity' => ['nullable', 'numeric', 'min:0', 'max:999999999.999'],
        ]);

        $items = collect($data['items'])->keyBy('id')->all();

        $service->updateItems(auth()->user(), $inventoryCount, $items);

        return redirect()
            ->route('inventory-counts.show', $inventoryCount)
            ->with('status', 'Itens da contagem atualizados com sucesso.');
    }

    public function finish(InventoryCount $inventoryCount, InventoryCountService $service): RedirectResponse
    {
        abort_unless($inventoryCount->company_id === auth()->user()->company_id, 404);

        $service->finish(auth()->user(), $inventoryCount);

        return redirect()
            ->route('inventory-counts.show', $inventoryCount)
            ->with('status', 'Contagem finalizada com sucesso.');
    }

    public function approve(InventoryCount $inventoryCount, InventoryCountService $service, StockMovementService $stockMovementService): RedirectResponse
    {
        abort_unless($inventoryCount->company_id === auth()->user()->company_id, 404);

        $service->approve(auth()->user(), $inventoryCount, $stockMovementService);

        return redirect()
            ->route('inventory-counts.show', $inventoryCount)
            ->with('status', 'Ajustes aprovados com sucesso.');
    }

    private function products()
    {
        return Product::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get();
    }
}
