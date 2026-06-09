<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_user_cannot_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'cliente']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }
}
