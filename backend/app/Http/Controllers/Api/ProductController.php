<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $term = $data['q'] ?? '';
        $perPage = $data['per_page'] ?? 15;

        $products = Product::with(['category', 'supplier'])
            ->where('company_id', $request->user()->company_id)
            ->when($term !== '', fn ($query) => $this->applySearch($query, $term))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return $this->paginated($products, fn (Product $product) => $this->productData($product), 'Produtos encontrados com sucesso');
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
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $term = $data['q'] ?? '';
        $perPage = $data['per_page'] ?? 15;

        $products = Product::with(['category', 'supplier'])
            ->where('company_id', $request->user()->company_id)
            ->when($term !== '', fn ($query) => $this->applySearch($query, $term))
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return $this->paginated($products, fn (Product $product) => $this->productData($product), 'Busca realizada com sucesso');
    }

    private function applySearch($query, string $term)
    {
        return $query->where(fn ($query) => $query
            ->where('name', 'like', "%{$term}%")
            ->orWhere('sku', 'like', "%{$term}%")
            ->orWhere('barcode', 'like', "%{$term}%"));
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

}
