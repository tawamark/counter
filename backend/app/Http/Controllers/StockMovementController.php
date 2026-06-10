<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockMovementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $companyId = auth()->user()->company_id;

        $filters = $request->validate([
            'product_id' => ['nullable', Rule::exists('products', 'id')->where('company_id', $companyId)],
            'type' => ['nullable', Rule::in(['entry', 'exit', 'adjustment'])],
            'user_id' => ['nullable', Rule::exists('users', 'id')->where('company_id', $companyId)],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $movements = StockMovement::with(['product', 'user'])
            ->where('company_id', $companyId)
            ->when($filters['product_id'] ?? null, fn ($query, $productId) => $query->where('product_id', $productId))
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->when($filters['user_id'] ?? null, fn ($query, $userId) => $query->where('user_id', $userId))
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('stock-movements.index', [
            'movements' => $movements,
            'products' => $this->products(),
            'users' => User::where('company_id', $companyId)->orderBy('name')->get(),
            'filters' => $filters,
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
