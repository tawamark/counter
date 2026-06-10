<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        Product::create([
            'company_id' => auth()->user()->company_id,
            ...$data,
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

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        $product->update($this->validateProduct($request, $product));

        return redirect()
            ->route('products.index')
            ->with('status', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        if ($product->stockMovements()->exists() || $product->inventoryCountItems()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Não é possível excluir um produto com movimentações ou contagens vinculadas.');
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('status', 'Produto excluído com sucesso.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $companyId = auth()->user()->company_id;

        return $request->validate([
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('company_id', $companyId)],
            'supplier_id' => ['nullable', Rule::exists('suppliers', 'id')->where('company_id', $companyId)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where('company_id', $companyId)->ignore($product),
            ],
            'barcode' => ['nullable', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:20'],
            'cost_price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'sale_price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'current_quantity' => ['required', 'numeric', 'min:0', 'max:999999999.999'],
        ]);
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
