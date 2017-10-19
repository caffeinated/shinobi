<?php

namespace Caffeinated\Shinobi\Tests\Models;

use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use ShinobiTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}