<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartAdminRestrictionTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create(['name' => 'Monitores', 'slug' => 'monitores']);

        $seller = User::factory()->create(['role' => 'usuario']);

        $this->product = Product::factory()->create([
            'name' => 'Monitor Gamer',
            'price' => 150000.00,
            'stock' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);
    }

    public function test_admin_cannot_add_product_to_cart(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 1]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $this->product->id,
        ]);
    }

    public function test_regular_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);

        $response = $this->actingAs($user)
            ->post(route('cart.add'), ['product_id' => $this->product->id, 'quantity' => 1]);

        $response->assertRedirect();
        $response->assertSessionHas('cart_success');

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
        ]);
    }

    public function test_admin_does_not_see_add_to_cart_button_on_product_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('products.show', $this->product));

        $response->assertOk();
        $response->assertDontSee('Agregar al carrito');
        $response->assertSee('Los administradores no pueden agregar productos al carrito');
    }

    public function test_regular_user_sees_add_to_cart_button_on_product_page(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);

        $response = $this->actingAs($user)->get(route('products.show', $this->product));

        $response->assertOk();
        $response->assertSee('Agregar al carrito');
    }

    public function test_admin_does_not_see_panel_de_vendedor_link_in_header(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Panel de vendedor');
    }

    public function test_regular_user_sees_panel_de_vendedor_link_in_header(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee('Panel de vendedor');
    }
}
