<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryCountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_inventory_count_with_selected_products(): void
    {
        [$user, $firstProduct] = $this->createUserAndProduct(5);
        $secondProduct = Product::create([
            'company_id' => $user->company_id,
            'name' => 'Mouse',
            'sku' => 'MOU-001',
            'current_quantity' => 8,
        ]);

        $response = $this->actingAs($user)
            ->post('/inventory-counts', [
                'title' => 'Contagem geral',
                'product_ids' => [$firstProduct->id, $secondProduct->id],
            ]);

        $count = InventoryCount::first();

        $response->assertRedirect("/inventory-counts/{$count->id}");

        $this->assertDatabaseHas('inventory_counts', [
            'company_id' => $user->company_id,
            'created_by' => $user->id,
            'title' => 'Contagem geral',
            'status' => 'open',
        ]);

        $this->assertDatabaseHas('inventory_count_items', [
            'inventory_count_id' => $count->id,
            'product_id' => $firstProduct->id,
            'system_quantity' => 5,
            'difference' => 0,
            'sync_status' => 'pending',
        ]);

        $this->assertDatabaseHas('inventory_count_items', [
            'inventory_count_id' => $count->id,
            'product_id' => $secondProduct->id,
            'system_quantity' => 8,
        ]);
    }

    public function test_user_can_view_inventory_count_list_and_detail(): void
    {
        [$user, $product] = $this->createUserAndProduct(5);

        $count = InventoryCount::create([
            'company_id' => $user->company_id,
            'created_by' => $user->id,
            'title' => 'Contagem semanal',
            'status' => 'open',
            'started_at' => now(),
        ]);

        $count->items()->create([
            'product_id' => $product->id,
            'system_quantity' => 5,
            'difference' => 0,
            'sync_status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get('/inventory-counts')
            ->assertOk()
            ->assertSee('Contagem semanal');

        $this->actingAs($user)
            ->get("/inventory-counts/{$count->id}")
            ->assertOk()
            ->assertSee('Contagem semanal')
            ->assertSee('Notebook');
    }

    public function test_user_cannot_include_product_from_another_company(): void
    {
        [$user] = $this->createUserAndProduct(5);
        [, $otherProduct] = $this->createUserAndProduct(4, 'Outra empresa', 'outro@counter.test');

        $this->actingAs($user)
            ->post('/inventory-counts', [
                'title' => 'Contagem inválida',
                'product_ids' => [$otherProduct->id],
            ])
            ->assertSessionHasErrors('product_ids.0');
    }

    public function test_user_cannot_view_inventory_count_from_another_company(): void
    {
        [$user] = $this->createUserAndProduct(5);
        [$otherUser] = $this->createUserAndProduct(4, 'Outra empresa', 'outro@counter.test');

        $count = InventoryCount::create([
            'company_id' => $otherUser->company_id,
            'created_by' => $otherUser->id,
            'title' => 'Contagem restrita',
            'status' => 'open',
        ]);

        $this->actingAs($user)
            ->get("/inventory-counts/{$count->id}")
            ->assertNotFound();
    }

    private function createUserAndProduct(float $quantity, string $companyName = 'Counter Demo', string $email = 'admin@counter.test'): array
    {
        $company = Company::create([
            'name' => $companyName,
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name' => 'Administrador',
            'email' => $email,
            'password' => 'password',
            'role' => 'admin',
        ]);

        $product = Product::create([
            'company_id' => $company->id,
            'name' => 'Notebook',
            'sku' => 'NOTE-'.str_replace('@counter.test', '', $email),
            'current_quantity' => $quantity,
        ]);

        return [$user, $product];
    }
}
