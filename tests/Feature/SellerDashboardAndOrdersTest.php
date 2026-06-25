<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerDashboardAndOrdersTest extends TestCase
{
    use RefreshDatabase;

    private User $seller;

    private User $buyer;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create(['role' => 'usuario']);
        $this->buyer = User::factory()->create(['role' => 'usuario']);
        $this->category = Category::create(['name' => 'Monitores', 'slug' => 'monitores']);
    }

    public function test_dashboard_shows_the_sellers_product_and_sales_counts(): void
    {
        Product::factory()->count(3)->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id]);
        $product = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id]);

        $order = Order::create(['user_id' => $this->buyer->id, 'total' => $product->price, 'status' => 'pending']);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 1, 'price' => $product->price]);

        $response = $this->actingAs($this->seller)->get(route('seller.dashboard'));

        $response->assertOk();
        $response->assertViewHas('productsCount', 4);
        $response->assertViewHas('salesCount', 1);
    }

    public function test_dashboard_counts_are_isolated_per_seller(): void
    {
        $otherSeller = User::factory()->create(['role' => 'usuario']);
        Product::factory()->count(2)->create(['user_id' => $otherSeller->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->seller)->get(route('seller.dashboard'));

        $response->assertOk();
        $response->assertViewHas('productsCount', 0);
        $response->assertViewHas('salesCount', 0);
    }

    public function test_guest_cannot_access_seller_dashboard(): void
    {
        $this->get(route('seller.dashboard'))->assertRedirect(route('login'));
    }

    public function test_orders_listing_only_includes_orders_containing_the_sellers_products(): void
    {
        $myProduct = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id, 'name' => 'Producto Propio']);
        $otherSeller = User::factory()->create(['role' => 'usuario']);
        $otherProduct = Product::factory()->create(['user_id' => $otherSeller->id, 'category_id' => $this->category->id, 'name' => 'Producto de Otro']);

        $myOrder = Order::create(['user_id' => $this->buyer->id, 'total' => $myProduct->price, 'status' => 'pending']);
        OrderItem::create(['order_id' => $myOrder->id, 'product_id' => $myProduct->id, 'quantity' => 1, 'price' => $myProduct->price]);

        $otherOrder = Order::create(['user_id' => $this->buyer->id, 'total' => $otherProduct->price, 'status' => 'pending']);
        OrderItem::create(['order_id' => $otherOrder->id, 'product_id' => $otherProduct->id, 'quantity' => 1, 'price' => $otherProduct->price]);

        $response = $this->actingAs($this->seller)->get(route('seller.orders'));

        $response->assertOk();
        $response->assertSee('Producto Propio');
        $response->assertDontSee('Producto de Otro');
    }

    public function test_compras_listing_only_shows_the_authenticated_users_own_orders(): void
    {
        $product = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id]);

        $myOrder = Order::create(['user_id' => $this->buyer->id, 'total' => $product->price, 'status' => 'pending', 'shipping_address' => 'Mi Direccion Unica']);
        OrderItem::create(['order_id' => $myOrder->id, 'product_id' => $product->id, 'quantity' => 1, 'price' => $product->price]);

        $otherBuyer = User::factory()->create(['role' => 'usuario']);
        $otherOrder = Order::create(['user_id' => $otherBuyer->id, 'total' => $product->price, 'status' => 'pending', 'shipping_address' => 'Direccion De Otro Usuario']);
        OrderItem::create(['order_id' => $otherOrder->id, 'product_id' => $product->id, 'quantity' => 1, 'price' => $product->price]);

        $response = $this->actingAs($this->buyer)->get(route('seller.compras'));

        $response->assertOk();
        $response->assertSee('Mi Direccion Unica');
        $response->assertDontSee('Direccion De Otro Usuario');
    }

    public function test_guest_cannot_access_orders_or_compras(): void
    {
        $this->get(route('seller.orders'))->assertRedirect(route('login'));
        $this->get(route('seller.compras'))->assertRedirect(route('login'));
    }
}
