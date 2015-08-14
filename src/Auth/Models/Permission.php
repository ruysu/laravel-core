<?php

namespace Ruysu\Core\Auth\Models;

use Ruysu\Core\Eloquent\Model;

class Permission extends Model
{

    /**
     * Roles that have this permission
     * @return Relation
     */
    public function roles()
    {
        return $this->belongsToMany('Ruysu\Core\Auth\Role');
    }

}
