<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartMutationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    private CartItem $cartItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'cliente']);

        $seller = User::factory()->create(['role' => 'cliente']);
        $category = Category::create(['name' => 'Monitores', 'slug' => 'monitores']);

        $this->product = Product::factory()->create([
            'stock' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        $cart = Cart::getOrCreate($this->user);
        $this->cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    public function test_owner_can_update_cart_item_quantity(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('cart.update', $this->cartItem), ['quantity' => 5]);

        $response->assertRedirect();
        $response->assertSessionHas('cart_success');
        $this->assertSame(5, $this->cartItem->refresh()->quantity);
    }

    public function test_cannot_update_cart_item_quantity_above_stock(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('cart.update', $this->cartItem), ['quantity' => 999]);

        $response->assertSessionHasErrors(['quantity']);
        $this->assertSame(2, $this->cartItem->refresh()->quantity);
    }

    public function test_user_cannot_update_another_users_cart_item(): void
    {
        $otherUser = User::factory()->create(['role' => 'cliente']);

        $response = $this->actingAs($otherUser)
            ->patch(route('cart.update', $this->cartItem), ['quantity' => 3]);

        $response->assertStatus(403);
        $this->assertSame(2, $this->cartItem->refresh()->quantity);
    }

    public function test_guest_cannot_update_cart_item(): void
    {
        $response = $this->patch(route('cart.update', $this->cartItem), ['quantity' => 3]);

        $response->assertRedirect(route('login'));
    }

    public function test_owner_can_delete_cart_item(): void
    {
        $response = $this->actingAs($this->user)
            ->delete(route('cart.destroy', $this->cartItem));

        $response->assertRedirect();
        $response->assertSessionHas('cart_success');
        $this->assertDatabaseMissing('cart_items', ['id' => $this->cartItem->id]);
    }

    public function test_user_cannot_delete_another_users_cart_item(): void
    {
        $otherUser = User::factory()->create(['role' => 'cliente']);

        $response = $this->actingAs($otherUser)
            ->delete(route('cart.destroy', $this->cartItem));

        $response->assertStatus(403);
        $this->assertDatabaseHas('cart_items', ['id' => $this->cartItem->id]);
    }

    public function test_guest_cannot_delete_cart_item(): void
    {
        $response = $this->delete(route('cart.destroy', $this->cartItem));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('cart_items', ['id' => $this->cartItem->id]);
    }
}
