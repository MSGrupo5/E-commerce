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

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $seller;
    private User $buyer;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create(['role' => 'usuario']);
        $this->buyer = User::factory()->create(['role' => 'usuario']);

        $category = Category::create(['name' => 'Monitores', 'slug' => 'monitores']);

        $product = Product::factory()->create([
            'name' => 'Monitor Gamer',
            'price' => 150000.00,
            'stock' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'user_id' => $this->seller->id,
        ]);

        $this->order = Order::create([
            'user_id' => $this->buyer->id,
            'total' => 300000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'transferencia',
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 150000.00,
        ]);
    }

    public function test_seller_can_mark_order_as_paid(): void
    {
        $response = $this->actingAs($this->seller)
           ->patch(route('seller.orders.status', $this->order), ['status' => 'paid']);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'paid',
        ]);
    }

    public function test_seller_can_cancel_order(): void
    {
        $response = $this->actingAs($this->seller)
            ->patch(route('seller.orders.status', $this->order), ['status' => 'cancelled']);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_seller_cannot_modify_non_pending_order(): void
    {
        $this->order->update(['status' => 'paid']);

        $response = $this->actingAs($this->seller)
            ->patch(route('seller.orders.status', $this->order), ['status' => 'cancelled']);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'paid',
        ]);
    }

    public function test_seller_without_products_in_order_cannot_change_status(): void
    {
        $otherSeller = User::factory()->create(['role' => 'usuario']);

        $response = $this->actingAs($otherSeller)
            ->patch(route('seller.orders.status', $this->order), ['status' => 'paid']);

        $response->assertForbidden();

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_cannot_modify_order_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->patch(route('seller.orders.status', $this->order), ['status' => 'paid']);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'pending',
        ]);
    }

    public function test_guest_cannot_modify_order_status(): void
    {
        $response = $this->patch(route('seller.orders.status', $this->order), ['status' => 'paid']);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'pending',
        ]);
    }

    public function test_seller_sees_action_buttons_on_pending_orders(): void
    {
        $response = $this->actingAs($this->seller)
            ->get(route('seller.orders'));

        $response->assertOk();
        $response->assertSee('Marcar como pagado');
        $response->assertSee('Cancelar');
    }

    public function test_seller_does_not_see_action_buttons_on_paid_orders(): void
    {
        $this->order->update(['status' => 'paid']);

        $response = $this->actingAs($this->seller)
            ->get(route('seller.orders'));

        $response->assertOk();
        $response->assertSee('Pedido completado');
        $response->assertDontSee('Marcar como pagado');
        $response->assertDontSee('Cancelar');
    }

    public function test_seller_does_not_see_action_buttons_on_cancelled_orders(): void
    {
        $this->order->update(['status' => 'cancelled']);

        $response = $this->actingAs($this->seller)
            ->get(route('seller.orders'));

        $response->assertOk();
        $response->assertSee('Pedido cancelado');
        $response->assertDontSee('Marcar como pagado');
        $response->assertDontSee('Cancelar');
    }

    public function test_invalid_status_is_rejected(): void
    {
        $response = $this->actingAs($this->seller)
            ->patch(route('seller.orders.status', $this->order), ['status' => 'shipped']);

        $response->assertSessionHasErrors('status');

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'pending',
        ]);
    }
}
