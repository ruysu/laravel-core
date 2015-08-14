<?php

namespace Ruysu\Core\Auth\Models;

use Ruysu\Core\Eloquent\Model;

class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Users that have this role
     * @return Relation
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    /**
     * Permissions granted to this role
     * @return Relation
     */
    public function permissions()
    {
        return $this->belongsToMany('Ruysu\Core\Auth\Permission');
    }

}
