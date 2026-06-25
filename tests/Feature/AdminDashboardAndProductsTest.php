<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardAndProductsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = Category::create(['name' => 'Monitores']);
    }

    public function test_dashboard_shows_global_totals(): void
    {
        Product::factory()->count(2)->create(['category_id' => $this->category->id]);
        User::factory()->count(3)->create(['role' => 'usuario']);

        $buyer = User::factory()->create(['role' => 'usuario']);
        Order::create(['user_id' => $buyer->id, 'total' => 1000, 'status' => 'pending']);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalProductos', 2);
        $response->assertViewHas('totalPedidos', 1);
        // 3 creados + el buyer = 4 con role 'usuario'
        $response->assertViewHas('totalUsuarios', 4);
    }

    public function test_guest_and_non_admin_cannot_access_product_management(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);

        $this->get(route('admin.productos.index'))->assertRedirect(route('login'));
        $this->actingAs($user)->get(route('admin.productos.index'))->assertForbidden();
    }

    public function test_admin_can_view_all_products_regardless_of_seller(): void
    {
        $sellerA = User::factory()->create(['role' => 'usuario']);
        $sellerB = User::factory()->create(['role' => 'usuario']);

        Product::factory()->create(['name' => 'Producto De A', 'user_id' => $sellerA->id, 'category_id' => $this->category->id]);
        Product::factory()->create(['name' => 'Producto De B', 'user_id' => $sellerB->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.productos.index'));

        $response->assertOk();
        $response->assertSee('Producto De A');
        $response->assertSee('Producto De B');
    }

    public function test_admin_can_filter_products_by_category(): void
    {
        $otherCategory = Category::create(['name' => 'Teclados']);

        Product::factory()->create(['name' => 'Monitor Curvo', 'category_id' => $this->category->id]);
        Product::factory()->create(['name' => 'Teclado Mecanico', 'category_id' => $otherCategory->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.productos.index', ['category_id' => $this->category->id]));

        $response->assertOk();
        $response->assertSee('Monitor Curvo');
        $response->assertDontSee('Teclado Mecanico');
    }

    public function test_admin_can_filter_products_by_seller(): void
    {
        $sellerA = User::factory()->create(['role' => 'usuario']);
        $sellerB = User::factory()->create(['role' => 'usuario']);

        Product::factory()->create(['name' => 'Producto De A', 'user_id' => $sellerA->id, 'category_id' => $this->category->id]);
        Product::factory()->create(['name' => 'Producto De B', 'user_id' => $sellerB->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.productos.index', ['seller' => $sellerA->id]));

        $response->assertOk();
        $response->assertSee('Producto De A');
        $response->assertDontSee('Producto De B');
    }
}
