<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToastTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_flash_message_is_shown_as_global_toast(): void
    {
        $user = $this->createAdmin();

        $this->followingRedirects()
            ->actingAs($user)
            ->post('/categories', [
                'name' => 'Eletrônicos',
            ])
            ->assertOk()
            ->assertSee('Tudo certo')
            ->assertSee('Categoria cadastrada com sucesso.');
    }

    public function test_error_flash_message_is_shown_as_global_toast(): void
    {
        $user = $this->createAdmin();

        $category = Category::create([
            'company_id' => $user->company_id,
            'name' => 'Eletrônicos',
        ]);

        Product::create([
            'company_id' => $user->company_id,
            'category_id' => $category->id,
            'name' => 'Notebook',
            'sku' => 'NOTE-001',
            'current_quantity' => 5,
        ]);

        $this->followingRedirects()
            ->actingAs($user)
            ->delete("/categories/{$category->id}")
            ->assertOk()
            ->assertSee('Atenção')
            ->assertSee('Não é possível excluir uma categoria com produtos vinculados.');
    }

    private function createAdmin(): User
    {
        $company = Company::create([
            'name' => 'Counter Demo',
        ]);

        return User::create([
            'company_id' => $company->id,
            'name' => 'Administrador',
            'email' => 'admin@counter.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }
}
