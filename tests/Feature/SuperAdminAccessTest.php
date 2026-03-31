<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Firm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test super admin redirects to their dashboard after login.
     */
    public function test_super_admin_redirects_to_their_dashboard_after_login(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->post('/login', [
            'email' => $superAdmin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('super.admin.dashboard'));
    }

    /**
     * Test super admin can access firm-restricted routes.
     */
    public function test_super_admin_can_access_firm_restricted_routes(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        // Access a route protected by CheckFirmAdmin
        $response = $this->actingAs($superAdmin)->get(route('firm.index'));

        $response->assertStatus(200);
    }

    /**
     * Test super admin can access subscription management routes.
     */
    public function test_super_admin_can_access_admin_restricted_routes(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        // Access a route protected by CheckAdminRole
        $response = $this->actingAs($superAdmin)->get(route('admin.subscriptions.index'));

        $response->assertStatus(200);
    }

    /**
     * Test employee cannot access firm-restricted routes.
     */
    public function test_employee_cannot_access_firm_restricted_routes(): void
    {
        $employee = User::factory()->create([
            'role' => 'employee',
        ]);

        $response = $this->actingAs($employee)->get(route('firm.index'));

        $response->assertStatus(403);
    }
}
