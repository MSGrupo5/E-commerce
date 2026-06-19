<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductDeletionTest extends TestCase
{
    use RefreshDatabase;

    // --- Seller: destroy() ---

    public function test_vendedor_puede_eliminar_producto_sin_pedidos_definitivamente(): void
    {
        Storage::fake('public');

        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);

        $image = UploadedFile::fake()->image('product.jpg');
        $imagePath = $image->store('products', 'public');

        $product = Product::factory()->create([
            'user_id'     => $seller->id,
            'category_id' => $category->id,
            'image'       => $imagePath,
        ]);

        $this->actingAs($seller)
            ->delete(route('seller.productos.destroy', $product))
            ->assertRedirect(route('seller.productos.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertNull(Product::withTrashed()->find($product->id));

        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_vendedor_no_puede_eliminar_definitivamente_producto_con_pedidos(): void
    {
        Storage::fake('public');

        $seller = User::factory()->create();
        $category = Category::create(['name' => 'Test Category']);

        $image = UploadedFile::fake()->image('product.jpg');
        $imagePath = $image->store('products', 'public');

        $product = Product::factory()->create([
            'user_id'     => $seller->id,
            'category_id' => $category->id,
            'image'       => $imagePath,
        ]);

        $buyer = User::factory()->create();
        $order = Order::create([
            'user_id' => $buyer->id,
            'total'   => $product->price,
            'status'  => 'pending',
        ]);
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => 1,
            'price'      => $product->price,
        ]);

        $this->actingAs($seller)
            ->delete(route('seller.productos.destroy', $product))
            ->assertRedirect(route('seller.productos.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $this->assertNotNull(Product::withTrashed()->find($product->id));
        $this->assertNull(Product::find($product->id));

        Storage::disk('public')->assertExists($imagePath);
    }

    // --- Admin: destroy() ---

    public function test_admin_puede_eliminar_producto_sin_pedidos_definitivamente(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Test Category']);

        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.productos.destroy', $product))
            ->assertRedirect(route('admin.productos.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertNull(Product::withTrashed()->find($product->id));
    }

    public function test_admin_no_puede_eliminar_definitivamente_producto_con_pedidos(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Test Category']);

        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $buyer = User::factory()->create();
        $order = Order::create([
            'user_id' => $buyer->id,
            'total'   => $product->price,
            'status'  => 'pending',
        ]);
        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => 1,
            'price'      => $product->price,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.productos.destroy', $product))
            ->assertRedirect(route('admin.productos.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $this->assertNotNull(Product::withTrashed()->find($product->id));
        $this->assertNull(Product::find($product->id));
    }
}
