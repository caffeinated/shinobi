<?php

namespace Caffeinated\Shinobi\Tests\Unit;

use Caffeinated\Shinobi\Tests\TestCase;
use Caffeinated\Shinobi\Tests\Models\User;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;

class UserTest extends TestCase
{
  public function test_has_admin_role()
  {
    $user = User::where('name', 'Admin')->first();
    
    $this->assertTrue($user->isRole('admin'));
    $this->assertTrue($user->isAdmin());
  }
  
  public function test_can_access_one_permission()
  {
    $user = User::where('name', 'Admin')->first();
    
    $this->assertTrue($user->can('access.admin'));
  }

  public function test_can_access_all_permission_from_same_role()
  {
    $user = User::where('name', 'Admin')->first();
    
    $this->assertTrue($user->can(['access.admin', 'edit.users']));
  }

  public function test_can_access_all_permission_from_different_roles()
  {
    $user = User::where('name', 'Admin')->first();
    
    $this->assertTrue($user->can(['access.admin', 'edit.users', 'view.users']));
  }

  public function test_can_access_user_permission()
  {
    $user = User::where('name', 'User')->first();
    $this->assertTrue($user->can('email.users'));
  }

  public function test_can_access_all_permission_from_user_and_role()
  {
    $user = User::where('name', 'User')->first();
    
    $this->assertTrue($user->can(['view.users', 'email.users']));
  }

  public function test_cant_access_all_permission()
  {
    $user = User::where('name', 'User')->first();
    
    $this->assertFalse($user->can(['access.admin']));
  }

  public function test_can_access_atleast_one_permission()
  {
    $user = User::where('name', 'User')->first();
    
    $this->assertTrue($user->canAtLeast(['edit.users', 'view.users']));
  }

  public function test_cant_access_atleast_one_permission()
  {
    $user = User::where('name', 'User')->first();

    $this->assertFalse($user->canAtLeast(['access.admin', 'edit.users']));
  }
  
  public function test_can_revoke_and_assign_role()
  {
    $user = User::where('name', 'User')->first();

    $roleId = $user->roles()->first()->id;
    $permission = $user->roles()->first()->permissions()->first()->slug;

    $user->revokeRole($roleId);
    $this->assertFalse($user->can($permission), 'User::revokeRoke failed');

    $user->assignRole($roleId);
    // not sure why, but $user->fresh() doesnt work here
    $user = User::where('name', 'User')->first();
    $this->assertTrue($user->can($permission), 'User::assignRole failed');

    $user->revokeAllRoles();
    $user = User::where('name', 'User')->first();
    $this->assertFalse($user->can($permission), 'User::revokeAllRoles failed');

    $user->syncRoles([$roleId]);
    $user = User::where('name', 'User')->first();
    $this->assertTrue($user->can($permission), 'User::syncRoles failed');
  }
  
  public function test_can_revoke_and_assign_role_by_slug()
  {
    $user = User::where('name', 'User')->first();

    $roleSlug = $user->roles()->first()->slug;
    $permission = $user->roles()->first()->permissions()->first()->slug;

    $user->revokeRole($roleSlug);
    $this->assertFalse($user->can($permission), 'User::revokeRoke failed');

    $user->assignRole($roleSlug);
    $user = User::where('name', 'User')->first();
    $this->assertTrue($user->can($permission), 'User::assignRole failed');
  }
  
  public function test_special_all_access()
  {
    $user = User::where('name', 'Super User')->first();
    
    $this->assertTrue($user->can('doesnt-even-exist-and-super-still-has-access'));
  }

  public function test_special_no_access()
  {
    $user = User::where('name', 'Disabled User')->first();
    
    $this->assertFalse($user->can('view.users'));
  }
}
