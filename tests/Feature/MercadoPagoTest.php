<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MercadoPagoTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'direccion_entrega' => 'Calle Falsa 123',
        ]);

        $this->category = Category::create(['name' => 'Electrónica']);

        $this->product = Product::factory()->create([
            'name' => 'Monitor Gamer',
            'price' => 150000.00,
            'stock' => 10,
            'category_id' => $this->category->id,
            'user_id' => User::factory()->create()->id,
        ]);
    }

    public function test_mercadopago_service_no_disponible_sin_token(): void
    {
        config(['services.mercadopago.key' => null]);

        $service = app(MercadoPagoService::class);

        $this->assertFalse($service->isAvailable());
    }

    public function test_mercadopago_service_disponible_con_token(): void
    {
        config(['services.mercadopago.key' => 'test-token']);

        $service = app(MercadoPagoService::class);

        $this->assertTrue($service->isAvailable());
    }

    public function test_checkout_muestra_opcion_mercadopago(): void
    {
        $cart = Cart::getOrCreate($this->user);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('checkout.index'));

        $response->assertOk();
        $response->assertSee('Mercado Pago');
        $response->assertSee('mercadopago');
    }

    public function test_checkout_con_mercadopago_crea_orden_y_redirige_sin_token(): void
    {
        config(['services.mercadopago.key' => null]);

        $cart = Cart::getOrCreate($this->user);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('checkout.store'), [
                'shipping_address' => 'Calle Falsa 123',
                'payment_method' => 'mercadopago',
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'payment_method' => 'mercadopago',
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $response->assertRedirect(route('checkout.confirmacion', $order));
        $response->assertSessionHas('info', 'Mercado Pago no está disponible. Tu pedido quedó registrado como pendiente.');
    }

    public function test_callback_success_marca_orden_como_pagada(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'mercadopago',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('checkout.mp.callback', [
                'status' => 'success',
                'external_reference' => $order->id,
                'preference_id' => 'pref-test-123',
            ]));

        $response->assertRedirect(route('checkout.confirmacion', $order));

        $order->refresh();
        $this->assertEquals('paid', $order->status);
        $this->assertEquals('pref-test-123', $order->mp_preference_id);
    }

    public function test_callback_pending_mantiene_orden_pendiente(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'mercadopago',
        ]);

        $this->actingAs($this->user)
            ->get(route('checkout.mp.callback', [
                'status' => 'pending',
                'external_reference' => $order->id,
                'preference_id' => 'pref-test-456',
            ]));

        $order->refresh();
        $this->assertEquals('pending', $order->status);
    }

    public function test_callback_failure_cancela_orden(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'mercadopago',
        ]);

        $this->actingAs($this->user)
            ->get(route('checkout.mp.callback', [
                'status' => 'failure',
                'external_reference' => $order->id,
                'preference_id' => 'pref-test-789',
            ]));

        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }

    public function test_callback_sin_external_reference_no_cambia_estado(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'mercadopago',
        ]);

        $this->actingAs($this->user)
            ->get(route('checkout.mp.callback', ['status' => 'success']));

        $order->refresh();
        $this->assertEquals('pending', $order->status);
    }

    public function test_callback_de_otro_usuario_no_muestra_confirmacion(): void
    {
        $otherUser = User::factory()->create();

        $order = Order::create([
            'user_id' => $otherUser->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'mercadopago',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('checkout.mp.callback', [
                'status' => 'success',
                'external_reference' => $order->id,
            ]));

        $response->assertRedirect(route('home'));
    }

    public function test_webhook_acepta_post_y_retorna_ok(): void
    {
        $response = $this->postJson(route('checkout.mp.webhook'), [
            'type' => 'payment',
            'data' => ['id' => '123456'],
        ]);

        $response->assertStatus(200);
        $response->assertSee('OK');
    }

    public function test_webhook_con_payload_invalido_retorna_ok_igual(): void
    {
        $response = $this->postJson(route('checkout.mp.webhook'), []);

        $response->assertStatus(200);
        $response->assertSee('OK');
    }

    public function test_ruta_webhook_no_requiere_autenticacion(): void
    {
        $response = $this->postJson(route('checkout.mp.webhook'), [
            'type' => 'payment',
            'data' => ['id' => '345678'],
        ]);

        $response->assertStatus(200);
    }

    public function test_pedido_confirmacion_muestra_metodo_pago_mercadopago(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'total' => 150000.00,
            'status' => 'pending',
            'shipping_address' => 'Calle Falsa 123',
            'payment_method' => 'mercadopago',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('checkout.confirmacion', $order));

        $response->assertOk();
        $response->assertSee('Mercado Pago');
    }
}
