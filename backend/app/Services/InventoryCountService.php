<?php

namespace App\Services;

use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
}
