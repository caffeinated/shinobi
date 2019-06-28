<?php

namespace Caffeinated\Shinobi\Tests;

use Caffeinated\Shinobi\Tests\User;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Tests\TestCase;
use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_given_a_permission()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertCount(0, $user->permissions);
        
        $user->givePermissionTo($permission);

        $this->assertCount(1, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_given_a_permission_by_slug()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertCount(0, $user->permissions);
        
        $user->givePermissionTo($permission->slug);

        $this->assertCount(1, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_given_multiple_permissions()
    {
        $user        = factory(User::class)->create();
        $permissions = factory(Permission::class, 5)->create();
        
        $this->assertCount(0, $user->permissions);

        $user->givePermissionTo($permissions);

        $this->assertCount(5, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_given_multiple_permissions_by_slug()
    {
        $user        = factory(User::class)->create();
        $permissions = factory(Permission::class, 5)->create()->pluck('slug');

        $this->assertCount(0, $user->permissions);
        
        $user->givePermissionTo($permissions);

        $this->assertCount(5, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_a_permission()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create();
        
        $user->givePermissionTo($permission);
        
        $this->assertCount(1, $user->permissions);

        $user->revokePermissionTo($permission);

        $this->assertCount(0, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_a_permission_by_slug()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create();
        
        $user->givePermissionTo($permission->slug);
        
        $this->assertCount(1, $user->permissions);

        $user->revokePermissionTo($permission->slug);

        $this->assertCount(0, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_multiple_permissions()
    {
        $user        = factory(User::class)->create();
        $permissions = factory(Permission::class, 5)->create();
        
        $user->givePermissionTo($permissions);
        
        $this->assertCount(5, $user->permissions);

        $user->revokePermissionTo($permissions);

        $this->assertCount(0, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_be_revoked_multiple_permissions_by_slugs()
    {
        $user        = factory(User::class)->create();
        $permissions = factory(Permission::class, 5)->create()->pluck('slug');
        
        $user->givePermissionTo($permissions);
        
        $this->assertCount(5, $user->permissions);

        $user->revokePermissionTo($permissions);

        $this->assertCount(0, $user->fresh()->permissions);
    }

    /** @test */
    public function it_can_assert_has_a_given_permission()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertFalse($user->hasPermissionTo($permission->slug));
        
        $user->givePermissionTo($permission);

        $this->assertTrue($user->fresh()->hasPermissionTo($permission->slug));
    }

    /** @test */
    public function it_can_assert_does_not_have_a_given_permission()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create();

        $this->assertFalse($user->hasPermissionTo($permission->slug));
    }

    /** @test */
    public function it_can_be_assigned_a_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertCount(0, $user->roles);
        
        $user->assignRoles($role);

        $this->assertCount(1, $user->fresh()->roles);
    }

    /** @test */
    public function it_can_be_assigned_a_role_by_slug()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertCount(0, $user->roles);
        
        $user->assignRoles($role->slug);

        $this->assertCount(1, $user->fresh()->roles);
    }

    /** @test */
    public function it_can_be_assigned_multiple_roles()
    {
        $user  = factory(User::class)->create();
        $roles = factory(Role::class, 3)->create();

        $this->assertCount(0, $user->roles);
        
        $user->assignRoles($roles);

        $this->assertCount(3, $user->fresh()->roles);
    }

    /** @test */
    public function it_can_be_assigned_multiple_roles_by_slugs()
    {
        $user  = factory(User::class)->create();
        $roles = factory(Role::class, 3)->create()->pluck('slug');

        $this->assertCount(0, $user->roles);
        
        $user->assignRoles($roles);

        $this->assertCount(3, $user->fresh()->roles);
    }

    /** @test */
    public function it_has_a_given_permission_through_role()
    {
        $this->withoutExceptionHandling();
        
        $user       = factory(User::class)->create();
        $role       = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();
        
        $role->givePermissionTo($permission);

        $this->assertFalse($user->hasPermissionTo($permission->slug));
        
        $user->assignRoles($role);

        // dd($permission->slug);

        $this->assertTrue($user->fresh()->hasPermissionTo($permission->slug));
    }

    /** @test */
    public function it_has_no_permissions_when_assigned_a_role_with_a_no_access_flag()
    {
        $user       = factory(User::class)->create();
        $role       = factory(Role::class)->create(['special' => 'no-access']);
        $permission = factory(Permission::class)->create();
        
        $user->givePermissionTo($permission);

        $this->assertTrue($user->fresh()->hasPermissionTo($permission->slug));
        
        $user->assignRoles($role);

        $this->assertFalse($user->fresh()->hasPermissionTo($permission->slug));
    }

    /** @test */
    public function it_can_verify_it_has_defined_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertFalse($user->fresh()->hasRole($role->slug));

        $user->assignRoles($role);

        $this->assertTrue($user->fresh()->hasRole($role->slug));
    }

    /** @test */
    public function it_can_verify_it_has_any_defined_role()
    {
        $editor = factory(Role::class)->create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);
            
        $moderator = factory(Role::class)->create([
            'name' => 'Moderator',
            'slug' => 'moderator',
        ]);
                
        $user = factory(User::class)->create();

        $this->assertFalse($user->fresh()->hasAnyRole('moderator', 'editor'));

        $user->assignRoles($editor);

        $this->assertTrue($user->fresh()->hasAnyRole('moderator', 'editor'));
    }

     /** @test */
    public function it_can_verify_it_has_all_defined_roles()
    {
        $editor = factory(Role::class)->create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);
            
        $moderator = factory(Role::class)->create([
            'name' => 'Moderator',
            'slug' => 'moderator',
        ]);
        
        $user = factory(User::class)->create();

        $this->assertFalse($user->fresh()->hasAllRoles('moderator', 'editor'));

        $user->assignRoles($editor);

        $this->assertFalse($user->fresh()->hasAllRoles('moderator', 'editor'));

        $user->assignRoles($moderator);

        $this->assertTrue($user->fresh()->hasAllRoles('moderator', 'editor'));
    }
}