<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryCount;
use App\Models\InventoryCountItem;
use App\Services\InventoryCountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryCountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $counts = InventoryCount::withCount('items')
            ->where('company_id', $request->user()->company_id)
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->get()
            ->map(fn (InventoryCount $count) => $this->countData($count));

        return $this->success($counts, 'Contagens encontradas com sucesso');
    }

    public function show(Request $request, InventoryCount $inventoryCount): JsonResponse
    {
        abort_unless($inventoryCount->company_id === $request->user()->company_id, 404);

        return $this->success($this->countData($inventoryCount->loadCount('items')), 'Contagem encontrada com sucesso');
    }

    public function items(Request $request, InventoryCount $inventoryCount): JsonResponse
    {
        abort_unless($inventoryCount->company_id === $request->user()->company_id, 404);

        $items = $inventoryCount->items()
            ->with('product')
            ->orderBy('id')
            ->get()
            ->map(fn (InventoryCountItem $item) => $this->itemData($item));

        return $this->success($items, 'Itens encontrados com sucesso');
    }

    public function updateItems(Request $request, InventoryCount $inventoryCount, InventoryCountService $service): JsonResponse
    {
        abort_unless($inventoryCount->company_id === $request->user()->company_id, 404);

        $itemIds = $inventoryCount->items()->pluck('id')->all();

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', Rule::in($itemIds)],
            'items.*.counted_quantity' => ['nullable', 'numeric', 'min:0', 'max:999999999.999'],
        ]);

        $items = collect($data['items'])->keyBy('id')->all();
        $count = $service->updateItems($request->user(), $inventoryCount, $items);

        return $this->success($this->countData($count->loadCount('items')), 'Itens sincronizados com sucesso');
    }

    private function countData(InventoryCount $count): array
    {
        return [
            'id' => $count->id,
            'title' => $count->title,
            'status' => $count->status,
            'items_count' => $count->items_count,
            'started_at' => $count->started_at?->toISOString(),
            'finished_at' => $count->finished_at?->toISOString(),
            'approved_at' => $count->approved_at?->toISOString(),
        ];
    }

    private function itemData(InventoryCountItem $item): array
    {
        return [
            'id' => $item->id,
            'product' => $item->product ? [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'sku' => $item->product->sku,
                'barcode' => $item->product->barcode,
                'unit' => $item->product->unit,
            ] : null,
            'system_quantity' => (float) $item->system_quantity,
            'counted_quantity' => $item->counted_quantity === null ? null : (float) $item->counted_quantity,
            'difference' => (float) $item->difference,
            'sync_status' => $item->sync_status,
            'counted_at' => $item->counted_at?->toISOString(),
        ];
    }

    private function success(mixed $data, string $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ]);
    }
}
