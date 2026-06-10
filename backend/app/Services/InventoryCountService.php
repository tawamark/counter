<?php

namespace App\Services;

use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryCountService
{
    public function create(User $user, string $title, array $productIds): InventoryCount
    {
        return DB::transaction(function () use ($user, $title, $productIds): InventoryCount {
            $products = Product::where('company_id', $user->company_id)
                ->whereIn('id', $productIds)
                ->orderBy('name')
                ->get();

            $count = InventoryCount::create([
                'company_id' => $user->company_id,
                'created_by' => $user->id,
                'title' => $title,
                'status' => 'open',
                'started_at' => now(),
            ]);

            foreach ($products as $product) {
                $count->items()->create([
                    'product_id' => $product->id,
                    'system_quantity' => $product->current_quantity,
                    'difference' => 0,
                    'sync_status' => 'pending',
                ]);
            }

            return $count;
        });
    }

    public function updateItems(User $user, InventoryCount $count, array $items): InventoryCount
    {
        if ($count->company_id !== $user->company_id) {
            abort(404);
        }

        if (in_array($count->status, ['finished', 'approved'], true)) {
            throw ValidationException::withMessages([
                'items' => 'Não é possível alterar uma contagem finalizada ou aprovada.',
            ]);
        }

        return DB::transaction(function () use ($user, $count, $items): InventoryCount {
            $count->load('items');

            foreach ($count->items as $item) {
                if (! array_key_exists($item->id, $items)) {
                    continue;
                }

                $countedQuantity = $items[$item->id]['counted_quantity'];

                if ($countedQuantity === null || $countedQuantity === '') {
                    $item->update([
                        'counted_by' => null,
                        'counted_quantity' => null,
                        'difference' => 0,
                        'sync_status' => 'pending',
                        'counted_at' => null,
                    ]);

                    continue;
                }

                $quantity = (float) $countedQuantity;

                $item->update([
                    'counted_by' => $user->id,
                    'counted_quantity' => $quantity,
                    'difference' => $quantity - (float) $item->system_quantity,
                    'sync_status' => 'synced',
                    'counted_at' => now(),
                ]);
            }

            if ($count->status === 'open') {
                $count->update([
                    'status' => 'in_progress',
                ]);
            }

            return $count->refresh();
        });
    }
}
