<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $seller;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'role' => 'cliente',
            'direccion_entrega' => 'Calle Falsa 123',
        ]);

        $this->seller = User::factory()->create([
            'name' => 'Vendedor Oficial Gamer',
            'role' => 'cliente',
        ]);
        
        $category = Category::create([
            'name' => 'Monitores',
            'slug' => 'monitores',
        ]);

        $this->product = Product::factory()->create([
            'name' => 'Monitor Gamer',
            'price' => 150000.00,
            'stock' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'user_id' => $this->seller->id,
        ]);
    }

    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_checkout_redirects_if_cart_is_empty(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('info', 'Tu carrito está vacío.');
    }

    public function test_checkout_page_displays_when_cart_has_items(): void
    {
        $cart = Cart::getOrCreate($this->user);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout.index'));

        $response->assertOk();
        $response->assertViewIs('pedido.index');
        $response->assertSee('Monitor Gamer');
        $response->assertSee('Calle Falsa 123'); // Pre-completado
    }

    public function test_checkout_process_creates_order_and_clears_cart(): void
    {
        $cart = Cart::getOrCreate($this->user);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('checkout.store'), [
                'shipping_address' => 'Nueva Direccion 456',
                'phone'            => '11 2345-6789',
                'payment_method'   => 'efectivo',
            ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);

        $response->assertRedirect(route('checkout.confirmacion', $order));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total' => 300000.00,
            'status' => 'pending',
            'shipping_address' => 'Nueva Direccion 456',
            'phone' => '11 2345-6789',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 150000.00,
        ]);

        // Stock reducido
        $this->product->refresh();
        $this->assertSame(8, $this->product->stock);

        // Carrito vacío
        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
        ]);
    }

    public function test_checkout_fails_if_stock_insufficient(): void
    {
        $cart = Cart::getOrCreate($this->user);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 12, // Mayor que stock (10)
        ]);

        $response = $this->actingAs($this->user)
            ->from(route('checkout.index'))
            ->post(route('checkout.store'), [
                'shipping_address' => 'Calle Falsa 123',
                'phone'            => '11 2345-6789',
                'payment_method'   => 'efectivo',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHasErrors(['stock']);

        // Verificar que no se creo la orden
        $this->assertDatabaseMissing('orders', [
            'user_id' => $this->user->id,
        ]);

        // Stock sin cambiar
        $this->product->refresh();
        $this->assertSame(10, $this->product->stock);
    }

    public function test_user_can_view_own_checkout_confirmation(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'efectivo',
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout.confirmacion', $order));

        $response->assertOk();
        $response->assertViewIs('pedido.confirmacion');
        $response->assertSee('¡Pedido confirmado!');
    }

    public function test_user_cannot_view_others_checkout_confirmation(): void
    {
        $otherUser = User::factory()->create(['role' => 'cliente']);
        $order = Order::create([
            'user_id' => $otherUser->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'efectivo',
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout.confirmacion', $order));

        $response->assertStatus(403);
    }

    public function test_user_can_submit_usdt_tx_hash_for_own_order(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'usdt',
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('checkout.comprobante', $order), ['usdt_tx_hash' => 'abc123hash']);

        $response->assertRedirect(route('checkout.confirmacion', $order));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'usdt_tx_hash' => 'abc123hash',
        ]);
    }

    public function test_user_cannot_submit_tx_hash_for_others_order(): void
    {
        $otherUser = User::factory()->create(['role' => 'cliente']);
        $order = Order::create([
            'user_id' => $otherUser->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'usdt',
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('checkout.comprobante', $order), ['usdt_tx_hash' => 'abc123hash']);

        $response->assertStatus(403);
    }

    public function test_cannot_submit_tx_hash_for_non_usdt_order(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'efectivo',
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('checkout.comprobante', $order), ['usdt_tx_hash' => 'abc123hash']);

        $response->assertStatus(404);
    }
}
