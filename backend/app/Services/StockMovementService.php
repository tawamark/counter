<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementService
{
    public function register(User $user, Product $product, string $type, float $quantity, ?string $reason = null): StockMovement
    {
        if ($product->company_id !== $user->company_id) {
            abort(404);
        }

        return DB::transaction(function () use ($user, $product, $type, $quantity, $reason): StockMovement {
            $product->refresh();

            $quantityBefore = (float) $product->current_quantity;
            $quantityAfter = $this->calculateQuantityAfter($type, $quantityBefore, $quantity);

            $product->update([
                'current_quantity' => $quantityAfter,
            ]);

            return StockMovement::create([
                'company_id' => $user->company_id,
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => $type,
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reason' => $reason,
            ]);
        });
    }

    private function calculateQuantityAfter(string $type, float $quantityBefore, float $quantity): float
    {
        if ($type === 'entry') {
            return $quantityBefore + $quantity;
        }

        if ($type === 'exit') {
            if ($quantity > $quantityBefore) {
                throw ValidationException::withMessages([
                    'quantity' => 'A quantidade de saída não pode ser maior que o saldo atual.',
                ]);
            }

            return $quantityBefore - $quantity;
        }

        if ($type === 'adjustment') {
            return $quantity;
        }

        throw ValidationException::withMessages([
            'type' => 'Tipo de movimentação inválido.',
        ]);
    }
}
