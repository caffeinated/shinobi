<?php

namespace Caffeinated\Shinobi\Tests;

use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Tests\TestCase;
use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_given_a_permission()
    {
        $role       = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertCount(0, $role->permissions);
        
        $role->givePermissionTo($permission);

        $this->assertCount(1, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_given_a_permission_by_slug()
    {
        $role       = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertCount(0, $role->permissions);
        
        $role->givePermissionTo($permission->slug);

        $this->assertCount(1, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_given_multiple_permissions()
    {
        $role        = factory(Role::class)->create();
        $permissions = factory(Permission::class, 5)->create();
        
        $this->assertCount(0, $role->permissions);

        $role->givePermissionTo($permissions);

        $this->assertCount(5, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_given_multiple_permissions_by_slug()
    {
        $role        = factory(Role::class)->create();
        $permissions = factory(Permission::class, 5)->create()->pluck('slug');

        $this->assertCount(0, $role->permissions);
        
        $role->givePermissionTo($permissions);

        $this->assertCount(5, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_a_permission()
    {
        $role       = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        
        $role->givePermissionTo($permission);
        
        $this->assertCount(1, $role->permissions);

        $role->revokePermissionTo($permission);

        $this->assertCount(0, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_a_permission_by_slug()
    {
        $role       = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        
        $role->givePermissionTo($permission->slug);
        
        $this->assertCount(1, $role->permissions);

        $role->revokePermissionTo($permission->slug);

        $this->assertCount(0, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_multiple_permissions()
    {
        $role        = factory(Role::class)->create();
        $permissions = factory(Permission::class, 5)->create();
        
        $role->givePermissionTo($permissions);
        
        $this->assertCount(5, $role->permissions);

        $role->revokePermissionTo($permissions);

        $this->assertCount(0, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_multiple_permissions_by_slugs()
    {
        $role        = factory(Role::class)->create();
        $permissions = factory(Permission::class, 5)->create()->pluck('slug');
        
        $role->givePermissionTo($permissions);
        
        $this->assertCount(5, $role->permissions);

        $role->revokePermissionTo($permissions);

        $this->assertCount(0, $role->fresh()->permissions);
    }

    /** @test */
    public function it_can_assert_has_a_given_permission()
    {
        $role       = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertFalse($role->hasPermissionTo($permission->slug));
        
        $role->givePermissionTo($permission);

        $this->assertTrue($role->fresh()->hasPermissionTo($permission->slug));
    }

    /** @test */
    public function it_can_assert_does_not_have_a_given_permission()
    {
        $role       = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertFalse($role->hasPermissionTo($permission->slug));
    }

    /** @test */
    public function it_can_assert_with_no_access_flag_never_has_permission()
    {
        $permission = factory(Permission::class)->create();
        $role       = factory(Role::class)->create([
            'special' => 'no-access',
        ]);

        $role->givePermissionTo($permission);

        $this->assertFalse($role->hasPermissionTo($permission->slug));
    }

    /** @test */
    public function it_can_assert_with_all_access_flag_always_has_permission()
    {
        $permission = factory(Permission::class)->create();
        $role       = factory(Role::class)->create([
            'special' => 'all-access',
        ]);

        $this->assertTrue($role->hasPermissionTo($permission->slug));
    }
}