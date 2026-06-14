<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_puede_ver_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_cliente_no_puede_ver_dashboard()
    {
        $cliente = User::factory()->create(['role' => 'cliente']);

        $response = $this->actingAs($cliente)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_invitado_es_redirigido_al_login()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }
}
