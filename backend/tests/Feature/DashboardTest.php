<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_company_totals_and_recent_movements(): void
    {
        $company = Company::create([
            'name' => 'Counter Demo',
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => 'Administrador',
            'email' => 'admin@counter.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $category = Category::create([
            'company_id' => $company->id,
            'name' => 'Eletrônicos',
        ]);

        $supplier = Supplier::create([
            'company_id' => $company->id,
            'name' => 'Fornecedor Demo',
        ]);

        $product = Product::create([
            'company_id' => $company->id,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'name' => 'Notebook',
            'sku' => 'NOTE-001',
            'current_quantity' => 5,
        ]);

        Product::create([
            'company_id' => $company->id,
            'name' => 'Mouse',
            'sku' => 'MOU-001',
            'current_quantity' => 12,
        ]);

        InventoryCount::create([
            'company_id' => $company->id,
            'created_by' => $user->id,
            'title' => 'Contagem inicial',
            'status' => 'open',
        ]);

        StockMovement::create([
            'company_id' => $company->id,
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'entry',
            'quantity' => 5,
            'quantity_before' => 0,
            'quantity_after' => 5,
            'reason' => 'Entrada inicial',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Produtos')
            ->assertSee('2')
            ->assertSee('Categorias')
            ->assertSee('Fornecedores')
            ->assertSee('Contagens abertas')
            ->assertSee('Notebook')
            ->assertSee('Administrador');
    }
}
