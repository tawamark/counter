<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'supplier'])
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->paginate(10);

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        return view('products.create', $this->formOptions());
    }

    public function store(ProductRequest $request, AuditLogService $auditLogService): RedirectResponse
    {
        $product = Product::create([
            'company_id' => $request->user()->company_id,
            ...$request->validated(),
        ]);

        $auditLogService->record(auth()->user(), 'produtos', 'criou', 'Produto cadastrado: '.$product->name, $product, [
            'sku' => $product->sku,
        ]);

        return redirect()
            ->route('products.index')
            ->with('status', 'Produto cadastrado com sucesso.');
    }

    public function edit(Product $product): View
    {
        $this->authorizeProduct($product);

        return view('products.edit', [
            'product' => $product,
            ...$this->formOptions(),
        ]);
    }

    public function update(ProductRequest $request, Product $product, AuditLogService $auditLogService): RedirectResponse
    {
        $this->authorizeProduct($product);

        $product->update($request->validated());

        $auditLogService->record(auth()->user(), 'produtos', 'atualizou', 'Produto atualizado: '.$product->name, $product, [
            'sku' => $product->sku,
        ]);

        return redirect()
            ->route('products.index')
            ->with('status', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product, AuditLogService $auditLogService): RedirectResponse
    {
        $this->authorizeProduct($product);

        if ($product->stockMovements()->exists() || $product->inventoryCountItems()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Não é possível excluir um produto com movimentações ou contagens vinculadas.');
        }

        $productName = $product->name;
        $productSku = $product->sku;

        $product->delete();

        $auditLogService->record(auth()->user(), 'produtos', 'excluiu', 'Produto excluído: '.$productName, null, [
            'sku' => $productSku,
        ]);

        return redirect()
            ->route('products.index')
            ->with('status', 'Produto excluído com sucesso.');
    }

    private function formOptions(): array
    {
        $companyId = auth()->user()->company_id;

        return [
            'categories' => Category::where('company_id', $companyId)->orderBy('name')->get(),
            'suppliers' => Supplier::where('company_id', $companyId)->orderBy('name')->get(),
        ];
    }

    private function authorizeProduct(Product $product): void
    {
        abort_unless($product->company_id === auth()->user()->company_id, 404);
    }
}
