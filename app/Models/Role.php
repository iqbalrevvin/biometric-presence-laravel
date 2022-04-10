<?php

namespace App\Models;

use App\Scopes\RoleNotDeveloperScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Models\Role as OriginalRole;

class Role extends OriginalRole
{
    use CrudTrait;

    protected $fillable = ['name', 'guard_name', 'updated_at', 'created_at'];

    protected static function booted()
    {
        static::addGlobalScope(new RoleNotDeveloperScope);
    }
}
