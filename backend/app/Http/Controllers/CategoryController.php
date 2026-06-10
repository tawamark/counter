<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('company_id', auth()->user()->company_id)
            ->withCount('products')
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where('company_id', auth()->user()->company_id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create([
            'company_id' => auth()->user()->company_id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()
            ->route('categories.index')
            ->with('status', 'Categoria cadastrada com sucesso.');
    }

    public function edit(Category $category): View
    {
        $this->authorizeCategory($category);

        return view('categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where('company_id', auth()->user()->company_id)->ignore($category),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('status', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorizeCategory($category);

        if ($category->products()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Não é possível excluir uma categoria com produtos vinculados.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('status', 'Categoria excluída com sucesso.');
    }

    private function authorizeCategory(Category $category): void
    {
        abort_unless($category->company_id === auth()->user()->company_id, 404);
    }
}
