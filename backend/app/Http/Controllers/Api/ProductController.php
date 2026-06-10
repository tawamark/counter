<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::with(['category', 'supplier'])
            ->where('company_id', $request->user()->company_id)
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product) => $this->productData($product));

        return $this->success($products, 'Produtos encontrados com sucesso');
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        abort_unless($product->company_id === $request->user()->company_id, 404);

        return $this->success($this->productData($product->load(['category', 'supplier'])), 'Produto encontrado com sucesso');
    }

    public function search(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $term = $data['q'] ?? '';

        $products = Product::with(['category', 'supplier'])
            ->where('company_id', $request->user()->company_id)
            ->when($term !== '', fn ($query) => $query
                ->where(fn ($query) => $query
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('barcode', 'like', "%{$term}%")))
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(fn (Product $product) => $this->productData($product));

        return $this->success($products, 'Busca realizada com sucesso');
    }

    private function productData(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'unit' => $product->unit,
            'cost_price' => (float) $product->cost_price,
            'sale_price' => (float) $product->sale_price,
            'current_quantity' => (float) $product->current_quantity,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ] : null,
            'supplier' => $product->supplier ? [
                'id' => $product->supplier->id,
                'name' => $product->supplier->name,
            ] : null,
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
