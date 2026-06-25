<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ProductPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ProductPolicy;
    }

    public function test_view_any_and_view_are_always_false(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($this->policy->viewAny($user));
        $this->assertFalse($this->policy->view($user, $this->makeProduct($user)));
    }

    public function test_any_authenticated_user_can_create(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_owner_can_update_delete_and_toggle_their_product(): void
    {
        $owner = User::factory()->create(['role' => 'usuario']);
        $product = $this->makeProduct($owner);

        $this->assertTrue($this->policy->update($owner, $product));
        $this->assertTrue($this->policy->delete($owner, $product));
        $this->assertTrue($this->policy->toggleActivo($owner, $product));
    }

    public function test_other_users_cannot_update_delete_or_toggle_someone_elses_product(): void
    {
        $owner = User::factory()->create(['role' => 'usuario']);
        $stranger = User::factory()->create(['role' => 'usuario']);
        $product = $this->makeProduct($owner);

        $this->assertFalse($this->policy->update($stranger, $product));
        $this->assertFalse($this->policy->delete($stranger, $product));
        $this->assertFalse($this->policy->toggleActivo($stranger, $product));
    }

    public function test_admin_can_update_delete_and_toggle_any_product(): void
    {
        $owner = User::factory()->create(['role' => 'usuario']);
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->makeProduct($owner);

        $this->assertTrue($this->policy->update($admin, $product));
        $this->assertTrue($this->policy->delete($admin, $product));
        $this->assertTrue($this->policy->toggleActivo($admin, $product));
    }

    private function makeProduct(User $owner): Product
    {
        $category = Category::create(['name' => 'Test', 'slug' => 'test-'.uniqid()]);

        return Product::factory()->create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
        ]);
    }
}
