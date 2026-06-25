<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $activeProduct;

    private Product $inactiveProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'cliente']);
        $seller = User::factory()->create(['role' => 'cliente']);
        $category = Category::create(['name' => 'Monitores', 'slug' => 'monitores']);

        $this->activeProduct = Product::factory()->create([
            'name' => 'Monitor Gamer Ultrawide Activo',
            'is_active' => true,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);

        $this->inactiveProduct = Product::factory()->create([
            'name' => 'Teclado Mecanico Descontinuado',
            'is_active' => false,
            'category_id' => $category->id,
            'user_id' => $seller->id,
        ]);
    }

    public function test_guest_cannot_access_favorites(): void
    {
        $response = $this->get(route('favorites.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_toggle_favorite(): void
    {
        $response = $this->post(route('favorites.toggle', $this->activeProduct));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_add_product_to_favorites(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('favorites.toggle', $this->activeProduct));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Producto agregado a favoritos.');

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'product_id' => $this->activeProduct->id,
        ]);
    }

    public function test_user_can_remove_product_from_favorites(): void
    {
        Favorite::create(['user_id' => $this->user->id, 'product_id' => $this->activeProduct->id]);

        $response = $this->actingAs($this->user)
            ->post(route('favorites.toggle', $this->activeProduct));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Producto eliminado de favoritos.');

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'product_id' => $this->activeProduct->id,
        ]);
    }

    public function test_favorites_index_only_shows_active_products(): void
    {
        Favorite::create(['user_id' => $this->user->id, 'product_id' => $this->activeProduct->id]);
        Favorite::create(['user_id' => $this->user->id, 'product_id' => $this->inactiveProduct->id]);

        $response = $this->actingAs($this->user)->get(route('favorites.index'));

        $response->assertOk();
        $response->assertSee($this->activeProduct->name);
        $response->assertDontSee($this->inactiveProduct->name);
    }

    public function test_toggling_favorite_only_affects_the_current_user(): void
    {
        $otherUser = User::factory()->create(['role' => 'cliente']);
        Favorite::create(['user_id' => $otherUser->id, 'product_id' => $this->activeProduct->id]);

        $this->actingAs($this->user)->post(route('favorites.toggle', $this->activeProduct));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'product_id' => $this->activeProduct->id,
        ]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $otherUser->id,
            'product_id' => $this->activeProduct->id,
        ]);
    }
}
