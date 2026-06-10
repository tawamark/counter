<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $company = Company::updateOrCreate([
            'name' => 'Counter Demo',
        ], [
            'document' => '00.000.000/0001-00',
            'phone' => '(11) 4002-8922',
            'email' => 'contato@counter.test',
            'address' => 'Rua Demo, 100',
        ]);

        $admin = $this->user($company, 'Administrador', 'admin@counter.test', 'admin');
        $stockist = $this->user($company, 'Estoquista', 'estoquista@counter.test', 'stockist');
        $counter = $this->user($company, 'Contador', 'contador@counter.test', 'counter');

        $electronics = Category::updateOrCreate([
            'company_id' => $company->id,
            'name' => 'Eletrônicos',
        ], [
            'description' => 'Produtos eletrônicos e periféricos',
        ]);

        $office = Category::updateOrCreate([
            'company_id' => $company->id,
            'name' => 'Escritório',
        ], [
            'description' => 'Materiais usados em ambiente administrativo',
        ]);

        $supplier = Supplier::updateOrCreate([
            'company_id' => $company->id,
            'name' => 'Fornecedor Demo',
        ], [
            'cnpj' => '12.345.678/0001-90',
            'phone' => '(11) 3000-0000',
            'email' => 'vendas@fornecedor.test',
            'address' => 'Avenida Estoque, 500',
        ]);

        $notebook = $this->product($company, $electronics, $supplier, [
            'name' => 'Notebook',
            'description' => 'Notebook para equipe administrativa',
            'sku' => 'NOTE-001',
            'barcode' => '789000000001',
            'unit' => 'un',
            'cost_price' => 2500,
            'sale_price' => 3200,
            'current_quantity' => 7,
        ]);

        $mouse = $this->product($company, $electronics, $supplier, [
            'name' => 'Mouse sem fio',
            'description' => 'Mouse óptico sem fio',
            'sku' => 'MOU-001',
            'barcode' => '789000000002',
            'unit' => 'un',
            'cost_price' => 45,
            'sale_price' => 79.9,
            'current_quantity' => 18,
        ]);

        $paper = $this->product($company, $office, $supplier, [
            'name' => 'Papel A4',
            'description' => 'Resma de papel sulfite A4',
            'sku' => 'PAP-001',
            'barcode' => '789000000003',
            'unit' => 'resma',
            'cost_price' => 18,
            'sale_price' => 29.9,
            'current_quantity' => 30,
        ]);

        $this->movement($company, $notebook, $stockist, 'entry', 10, 0, 10, 'Compra inicial');
        $this->movement($company, $notebook, $stockist, 'exit', 3, 10, 7, 'Retirada para uso interno');
        $this->movement($company, $mouse, $stockist, 'entry', 15, 0, 15, 'Compra inicial');
        $this->movement($company, $mouse, $stockist, 'adjustment', 18, 15, 18, 'Ajuste aprovado pela contagem piloto');
        $this->movement($company, $paper, $stockist, 'entry', 30, 0, 30, 'Compra inicial');

        $count = InventoryCount::updateOrCreate([
            'company_id' => $company->id,
            'title' => 'Contagem piloto',
        ], [
            'created_by' => $admin->id,
            'status' => 'in_progress',
            'started_at' => now()->subDay(),
            'finished_at' => null,
            'approved_at' => null,
        ]);

        $this->countItem($count, $notebook, $counter, 10, 7);
        $this->countItem($count, $mouse, $counter, 15, 18);
        $this->countItem($count, $paper, $counter, 30, 30);
    }

    private function user(Company $company, string $name, string $email, string $role): User
    {
        return User::updateOrCreate([
            'email' => $email,
        ], [
            'company_id' => $company->id,
            'name' => $name,
            'password' => 'password',
            'role' => $role,
        ]);
    }

    private function product(Company $company, Category $category, Supplier $supplier, array $data): Product
    {
        return Product::updateOrCreate([
            'company_id' => $company->id,
            'sku' => $data['sku'],
        ], [
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            ...$data,
        ]);
    }

    private function movement(Company $company, Product $product, User $user, string $type, float $quantity, float $before, float $after, string $reason): StockMovement
    {
        return StockMovement::updateOrCreate([
            'company_id' => $company->id,
            'product_id' => $product->id,
            'type' => $type,
            'reason' => $reason,
        ], [
            'user_id' => $user->id,
            'quantity' => $quantity,
            'quantity_before' => $before,
            'quantity_after' => $after,
        ]);
    }

    private function countItem(InventoryCount $count, Product $product, User $counter, float $systemQuantity, float $countedQuantity): void
    {
        $count->items()->updateOrCreate([
            'product_id' => $product->id,
        ], [
            'counted_by' => $counter->id,
            'system_quantity' => $systemQuantity,
            'counted_quantity' => $countedQuantity,
            'difference' => $countedQuantity - $systemQuantity,
            'sync_status' => 'synced',
            'counted_at' => now(),
        ]);
    }
}
