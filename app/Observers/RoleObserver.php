<?php

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{
    public function retrieved(Role $role)
    {
        $role->where('id', '=', 1);
    }
}
