<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(): View
    {
        $movements = StockMovement::with(['product', 'user'])
            ->where('company_id', auth()->user()->company_id)
            ->latest()
            ->paginate(15);

        return view('stock-movements.index', [
            'movements' => $movements,
        ]);
    }

    public function create(): View
    {
        return view('stock-movements.create', [
            'products' => $this->products(),
        ]);
    }

    public function store(Request $request, StockMovementService $service): RedirectResponse
    {
        $companyId = auth()->user()->company_id;

        $data = $request->validate([
            'product_id' => ['required', Rule::exists('products', 'id')->where('company_id', $companyId)],
            'type' => ['required', Rule::in(['entry', 'exit', 'adjustment'])],
            'quantity' => ['required', 'numeric', 'min:0.001', 'max:999999999.999'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::where('company_id', $companyId)->findOrFail($data['product_id']);

        $service->register(
            auth()->user(),
            $product,
            $data['type'],
            (float) $data['quantity'],
            $data['reason'] ?? null
        );

        return redirect()
            ->route('stock-movements.index')
            ->with('status', 'Movimentação registrada com sucesso.');
    }

    private function products()
    {
        return Product::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get();
    }
}
