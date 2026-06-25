<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_and_non_admin_cannot_access_user_management(): void
    {
        $user = User::factory()->create(['role' => 'usuario']);

        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($user)->patch(route('admin.users.toggle', $user))->assertForbidden();
    }

    public function test_admin_can_view_the_user_list(): void
    {
        User::factory()->create(['name' => 'Usuario De Prueba', 'role' => 'usuario']);

        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee('Usuario De Prueba');
    }

    public function test_admin_can_filter_users_by_active_status(): void
    {
        User::factory()->create(['name' => 'Usuario Activo', 'role' => 'usuario', 'is_active' => true]);
        User::factory()->create(['name' => 'Usuario Inactivo', 'role' => 'usuario', 'is_active' => false]);

        $response = $this->actingAs($this->admin)->get(route('admin.users.index', ['status' => 'active']));

        $response->assertOk();
        $response->assertSee('Usuario Activo');
        $response->assertDontSee('Usuario Inactivo');
    }

    public function test_admin_can_deactivate_a_regular_user(): void
    {
        $user = User::factory()->create(['role' => 'usuario', 'is_active' => true]);

        $response = $this->actingAs($this->admin)->patch(route('admin.users.toggle', $user));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertFalse($user->refresh()->is_active);
    }

    public function test_admin_can_reactivate_a_deactivated_user(): void
    {
        $user = User::factory()->create(['role' => 'usuario', 'is_active' => false]);

        $response = $this->actingAs($this->admin)->patch(route('admin.users.toggle', $user));

        $response->assertRedirect();
        $this->assertTrue($user->refresh()->is_active);
    }

    public function test_admin_cannot_deactivate_another_admin(): void
    {
        $otherAdmin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($this->admin)->patch(route('admin.users.toggle', $otherAdmin));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertTrue($otherAdmin->refresh()->is_active);
    }
}
