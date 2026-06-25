<?php

declare(strict_types=1);

namespace Tests\Feature\Seller;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceTest extends TestCase
{
    use RefreshDatabase;

    private User $seller;
    private User $buyer;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create(['role' => 'usuario']);
        $this->buyer = User::factory()->create(['role' => 'usuario']);

        $category = Category::create(['name' => 'Monitores', 'slug' => 'monitores']);

        $this->product = Product::factory()->create([
            'name' => 'Monitor Gamer',
            'price' => 150000.00,
            'stock' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'user_id' => $this->seller->id,
        ]);
    }

    private function createPaidOrder(int $quantity): Order
    {
        $order = Order::create([
            'user_id' => $this->buyer->id,
            'total' => $this->product->price * $quantity,
            'status' => 'paid',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'transferencia',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => $quantity,
            'price' => $this->product->price,
        ]);

        return $order;
    }

    public function test_seller_can_view_balance_page(): void
    {
        $response = $this->actingAs($this->seller)->get(route('seller.balance'));

        $response->assertOk();
        $response->assertViewIs('seller.balance');
    }

    public function test_balance_shows_correct_amounts_with_paid_orders(): void
    {
        $this->createPaidOrder(2);
        $this->createPaidOrder(3);

        $response = $this->actingAs($this->seller)->get(route('seller.balance'));

        $response->assertOk();
        $response->assertSee('Ventas Brutas');
        $response->assertSee('Comisión');
        $response->assertSee('Saldo Disponible');

        $expectedBruto = 150000 * 5; // 2 + 3 = 5 units
        $expectedComision = $expectedBruto * 0.10;
        $expectedNeto = $expectedBruto - $expectedComision;

        $response->assertSee('$' . number_format($expectedBruto, 0, ',', '.'));
        $response->assertSee('$' . number_format($expectedComision, 0, ',', '.'));
        $response->assertSee('$' . number_format($expectedNeto, 0, ',', '.'));
    }

    public function test_balance_excludes_non_paid_orders(): void
    {
        $this->createPaidOrder(2);

        $order = Order::create([
            'user_id' => $this->buyer->id,
            'total' => $this->product->price * 1,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'transferencia',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $response = $this->actingAs($this->seller)->get(route('seller.balance'));

        $expectedBruto = 150000 * 2;
        $response->assertSee('$' . number_format($expectedBruto, 0, ',', '.'));
    }

    public function test_balance_shows_empty_state_without_paid_orders(): void
    {
        $response = $this->actingAs($this->seller)->get(route('seller.balance'));

        $response->assertOk();
        $response->assertSee('No hay ventas pagadas todavía');
    }

    public function test_balance_excludes_other_sellers_products(): void
    {
        $this->createPaidOrder(2);

        $otherSeller = User::factory()->create(['role' => 'usuario']);

        $response = $this->actingAs($otherSeller)->get(route('seller.balance'));

        $response->assertOk();
        $response->assertSee('No hay ventas pagadas todavía');
    }

    public function test_admin_cannot_access_balance_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('seller.balance'));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_guest_cannot_access_balance_page(): void
    {
        $response = $this->get(route('seller.balance'));

        $response->assertRedirect(route('login'));
    }

    public function test_commission_rate_is_configurable(): void
    {
        config(['marketplace.commission_rate' => 15]);

        $this->createPaidOrder(2);

        $response = $this->actingAs($this->seller)->get(route('seller.balance'));

        $expectedBruto = 150000 * 2;
        $expectedComision = $expectedBruto * 0.15;
        $expectedNeto = $expectedBruto - $expectedComision;

        $response->assertSee('Comisión (15%)');
        $response->assertSee('$' . number_format($expectedComision, 0, ',', '.'));
        $response->assertSee('$' . number_format($expectedNeto, 0, ',', '.'));
    }

    public function test_seller_dashboard_has_balance_link(): void
    {
        $response = $this->actingAs($this->seller)->get(route('seller.dashboard'));

        $response->assertOk();
        $response->assertSee('Ver Saldo');
    }
}
