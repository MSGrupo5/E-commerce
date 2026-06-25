<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_and_non_admin_cannot_manage_categories(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);
        $category = Category::create(['name' => 'Monitores']);

        $this->get(route('admin.categorias.index'))->assertRedirect(route('login'));
        $this->actingAs($user)->get(route('admin.categorias.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.categorias.store'), ['name' => 'Otra'])->assertForbidden();
        $this->actingAs($user)->patch(route('admin.categorias.update', $category), ['name' => 'Otra'])->assertForbidden();
        $this->actingAs($user)->delete(route('admin.categorias.destroy', $category))->assertForbidden();
    }

    public function test_admin_can_view_categories_with_product_counts(): void
    {
        $category = Category::create(['name' => 'Monitores']);
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.categorias.index'));

        $response->assertOk();
        $response->assertSee('Monitores');
    }

    public function test_admin_can_create_a_category(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.categorias.store'), ['name' => 'Periféricos']);

        $response->assertRedirect(route('admin.categorias.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['name' => 'Periféricos']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Periféricos']);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.categorias.store'), ['name' => 'Periféricos']);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_admin_can_update_a_category_name(): void
    {
        $category = Category::create(['name' => 'Nombre viejo']);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.categorias.update', $category), ['name' => 'Nombre nuevo']);

        $response->assertRedirect(route('admin.categorias.index'));
        $this->assertSame('Nombre nuevo', $category->refresh()->name);
    }

    public function test_updating_a_category_can_keep_its_own_name(): void
    {
        $category = Category::create(['name' => 'Monitores']);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.categorias.update', $category), ['name' => 'Monitores']);

        $response->assertSessionHasNoErrors();
    }

    public function test_admin_cannot_rename_a_category_to_a_name_already_used_by_another(): void
    {
        Category::create(['name' => 'Monitores']);
        $category = Category::create(['name' => 'Teclados']);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.categorias.update', $category), ['name' => 'Monitores']);

        $response->assertSessionHasErrors(['name']);
        $this->assertSame('Teclados', $category->refresh()->name);
    }

    public function test_admin_can_delete_a_category_without_products(): void
    {
        $category = Category::create(['name' => 'Sin productos']);

        $response = $this->actingAs($this->admin)->delete(route('admin.categorias.destroy', $category));

        $response->assertRedirect(route('admin.categorias.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_a_category_with_products(): void
    {
        $category = Category::create(['name' => 'Con productos']);
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categorias.destroy', $category));

        $response->assertRedirect(route('admin.categorias.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
