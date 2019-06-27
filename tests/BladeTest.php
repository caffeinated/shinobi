<?php

namespace Caffeinated\Shinobi\Tests;

use Caffeinated\Shinobi\Tests\User;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Tests\TestCase;
use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BladeTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function the_can_directive_evaluates_true_when_permissions_are_met()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create([
            'name' => 'Test Permission',
            'slug' => 'test.permission',
        ]);

        $user->givePermissionTo($permission);

        $this->actingAs($user);

        $result = $this->renderView('can_directive');

        $this->assertEquals($result, 'has permission');
    }

    /** @test */
    public function the_can_directive_evaluates_false_when_permissions_are_not_met()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $result = $this->renderView('can_directive');

        $this->assertEquals($result, 'does not have permission');
    }

    /** @test */
    public function the_cannot_directive_evaluates_true_when_permissions_are_not_met()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $result = $this->renderView('cannot_directive');

        $this->assertEquals($result, 'does not have permission');
    }

    /** @test */
    public function the_cannot_directive_evaluates_false_when_permissions_are_met()
    {
        $user       = factory(User::class)->create();
        $permission = factory(Permission::class)->create([
            'name' => 'Test Permission',
            'slug' => 'test.permission',
        ]);

        $user->givePermissionTo($permission);

        $this->actingAs($user);

        $result = $this->renderView('cannot_directive');

        $this->assertEquals($result, 'has permission');
    }

    /** @test */
    public function the_role_directive_evaluates_true_when_user_has_given_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        $user->assignRoles($role);

        $this->actingAs($user);

        $result = $this->renderView('role_directive');

        $this->assertEquals($result, 'has admin role');
    }

    /** @test */
    public function the_role_directive_evaluates_true_if_previous_evaluations_didnt_pass_when_using_else()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create([
            'name' => 'Moderator',
            'slug' => 'moderator',
        ]);

        $user->assignRoles($role);

        $this->actingAs($user);

        $result = $this->renderView('role_directive');

        $this->assertEquals($result, 'has moderator role');
    }

    /** @test */
    public function the_role_directive_evaluates_false_when_user_does_not_have_given_roles()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $result = $this->renderView('role_directive');

        $this->assertEquals($result, 'does not have admin or moderator roles');
    }

    /** @test */
    public function guests_do_not_have_roles()
    {
        $result = $this->renderView('role_directive');

        $this->assertEquals($result, 'does not have admin or moderator roles');
    }
}