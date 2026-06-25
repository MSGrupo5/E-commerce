<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellerProductTest extends TestCase
{
    use RefreshDatabase;

    private User $seller;

    private User $otherSeller;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create(['role' => 'usuario']);
        $this->otherSeller = User::factory()->create(['role' => 'usuario']);
        $this->category = Category::create(['name' => 'Monitores', 'slug' => 'monitores']);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Monitor Gamer 24 pulgadas',
            'description' => 'Excelente estado, poco uso.',
            'price' => 150000,
            'stock' => 5,
            'category_id' => $this->category->id,
            'image' => UploadedFile::fake()->image('product.jpg'),
        ], $overrides);
    }

    public function test_index_only_lists_the_authenticated_sellers_products(): void
    {
        $own = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id, 'name' => 'Mi Producto']);
        Product::factory()->create(['user_id' => $this->otherSeller->id, 'category_id' => $this->category->id, 'name' => 'Producto Ajeno']);

        $response = $this->actingAs($this->seller)->get(route('seller.productos.index'));

        $response->assertOk();
        $response->assertSee('Mi Producto');
        $response->assertDontSee('Producto Ajeno');
    }

    public function test_create_form_can_be_rendered(): void
    {
        $response = $this->actingAs($this->seller)->get(route('seller.productos.create'));

        $response->assertOk();
        $response->assertViewIs('seller.products.form');
    }

    public function test_seller_can_store_a_new_product_with_an_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->seller)
            ->post(route('seller.productos.store'), $this->validPayload());

        $response->assertRedirect(route('seller.productos.index'));
        $response->assertSessionHas('success');

        $product = Product::where('name', 'Monitor Gamer 24 pulgadas')->first();
        $this->assertNotNull($product);
        $this->assertSame($this->seller->id, $product->user_id);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_store_requires_an_image(): void
    {
        $response = $this->actingAs($this->seller)
            ->post(route('seller.productos.store'), $this->validPayload(['image' => null]));

        $response->assertSessionHasErrors(['image']);
        $this->assertDatabaseMissing('products', ['name' => 'Monitor Gamer 24 pulgadas']);
    }

    public function test_store_rejects_an_offensive_product_name(): void
    {
        $response = $this->actingAs($this->seller)
            ->post(route('seller.productos.store'), $this->validPayload(['name' => 'producto trucho hdp']));

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('products', ['user_id' => $this->seller->id]);
    }

    public function test_show_redirects_owner_to_the_edit_form(): void
    {
        $product = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->seller)->get(route('seller.productos.show', $product));

        $response->assertRedirect(route('seller.productos.edit', $product));
    }

    public function test_show_is_forbidden_for_a_non_owner(): void
    {
        $product = Product::factory()->create(['user_id' => $this->otherSeller->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->seller)->get(route('seller.productos.show', $product));

        $response->assertStatus(403);
    }

    public function test_seller_can_view_edit_form_for_their_own_product(): void
    {
        $product = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->seller)->get(route('seller.productos.edit', $product));

        $response->assertOk();
    }

    public function test_seller_cannot_view_edit_form_for_a_product_they_do_not_own(): void
    {
        $product = Product::factory()->create(['user_id' => $this->otherSeller->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->seller)->get(route('seller.productos.edit', $product));

        $response->assertStatus(403);
    }

    public function test_seller_can_update_their_own_product(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'name' => 'Nombre viejo',
        ]);

        $response = $this->actingAs($this->seller)
            ->put(route('seller.productos.update', $product), $this->validPayload(['name' => 'Nombre actualizado', 'image' => null]));

        $response->assertRedirect(route('seller.productos.index'));
        $this->assertSame('Nombre actualizado', $product->refresh()->name);
    }

    public function test_update_replaces_the_image_and_deletes_the_old_one(): void
    {
        Storage::fake('public');

        $oldImage = UploadedFile::fake()->image('old.jpg')->store('products', 'public');

        $product = Product::factory()->create([
            'user_id' => $this->seller->id,
            'category_id' => $this->category->id,
            'image' => $oldImage,
        ]);

        $response = $this->actingAs($this->seller)
            ->put(route('seller.productos.update', $product), $this->validPayload(['image' => UploadedFile::fake()->image('new.jpg')]));

        $response->assertRedirect(route('seller.productos.index'));

        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($product->refresh()->image);
    }

    public function test_seller_cannot_update_a_product_they_do_not_own(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->otherSeller->id,
            'category_id' => $this->category->id,
            'name' => 'Nombre original',
        ]);

        $response = $this->actingAs($this->seller)
            ->put(route('seller.productos.update', $product), $this->validPayload(['image' => null]));

        $response->assertStatus(403);
        $this->assertSame('Nombre original', $product->refresh()->name);
    }

    public function test_seller_can_toggle_activo_on_their_own_product(): void
    {
        $product = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id, 'is_active' => true]);

        $response = $this->actingAs($this->seller)->patch(route('seller.productos.toggle-activo', $product));

        $response->assertRedirect();
        $this->assertFalse($product->refresh()->is_active);
    }

    public function test_seller_cannot_toggle_activo_on_a_product_they_do_not_own(): void
    {
        $product = Product::factory()->create(['user_id' => $this->otherSeller->id, 'category_id' => $this->category->id, 'is_active' => true]);

        $response = $this->actingAs($this->seller)->patch(route('seller.productos.toggle-activo', $product));

        $response->assertStatus(403);
        $this->assertTrue($product->refresh()->is_active);
    }

    public function test_seller_cannot_destroy_a_product_they_do_not_own(): void
    {
        $product = Product::factory()->create(['user_id' => $this->otherSeller->id, 'category_id' => $this->category->id]);

        $response = $this->actingAs($this->seller)->delete(route('seller.productos.destroy', $product));

        $response->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_guest_cannot_access_any_seller_product_route(): void
    {
        $product = Product::factory()->create(['user_id' => $this->seller->id, 'category_id' => $this->category->id]);

        $this->get(route('seller.productos.index'))->assertRedirect(route('login'));
        $this->get(route('seller.productos.create'))->assertRedirect(route('login'));
        $this->get(route('seller.productos.edit', $product))->assertRedirect(route('login'));
    }
}
