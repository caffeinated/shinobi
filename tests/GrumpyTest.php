<?php

namespace Caffeinated\Shinobi\Tests;

use Illuminate\Support\Facades\Schema;
use Caffeinated\Shinobi\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GrumpyTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_returns_true()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_has_necessary_tables()
    {
        $this->assertTrue(Schema::hasTable('roles'));
        $this->assertTrue(Schema::hasTable('permissions'));
        $this->assertTrue(Schema::hasTable('role_user'));
        $this->assertTrue(Schema::hasTable('permission_role'));
        $this->assertTrue(Schema::hasTable('permission_user'));
    }
}