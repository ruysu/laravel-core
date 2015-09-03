<?php

namespace Ruysu\Core\Auth\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Arr;
use Ruysu\Core\Eloquent\Model;

abstract class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'roles'];

    /**
     * Additional attributes appended to the model
     * @var array
     */
    protected $appends = ['permissions', 'plain_permissions'];

    /**
     * Additional date attributes of the model
     * @var array
     */
    protected $dates = ['login_at', 'last_login_at'];

    /**
     * Roles that have this permission
     * @return Relation
     */
    public function roles()
    {
        return $this->belongsToMany('Ruysu\Core\Auth\Models\Role');
    }

    /**
     * Filter results by a user level
     * @param  Builder $query
     * @return void
     */
    public function scopeOfLevel($query)
    {
        $args = func_get_args();
        $levels = array_splice($args, 1);
        $query->whereIn('level', $levels);
    }

    /**
     * Get the role ids
     * @return array
     */
    public function getRoleIdsAttribute()
    {
        if (!array_key_exists('roles', $this->relations)) {
            $this->load('roles');
        }

        return $this->relations['roles']->lists('id');
    }

    /**
     * Get the permissions by role
     * @return array
     */
    public function getPermissionsAttribute()
    {
        if (!array_key_exists('roles', $this->relations)) {
            $this->load('roles', 'roles.permissions');
        }

        if (empty($this->relations['roles'])) {
            return [];
        }

        // load permissions in case they have not been loaded
        if (
            ($role = $this->relations['roles']->first()) &&
            !array_key_exists('permissions', $role->getRelations())
        ) {
            $this->relations['roles']->load('permissions');
        }

        $roles = $this->relations['roles']->lists('name')->toArray();

        $permissions = $this->relations['roles']
            ->pluck('permissions')
            ->transform(function ($permissions) {
                return $permissions ? $permissions->lists('name', 'slug') : [];
            })->toArray();

        return array_combine($roles, $permissions);
    }

    /**
     * Get plain list of all permissions regardless of role
     * @return array
     */
    public function getPlainPermissionsAttribute()
    {
        $permissions = $this->getPermissionsAttribute();

        if (count($permissions)) {
            return call_user_func_array('array_merge', array_values($permissions));
        }

        return [];
    }

    /**
     * Determine if a user is an Admin
     * @return boolean
     */
    public function getIsAdminAttribute()
    {
        return (int) $this->getAttributeFromArray('level') == 255;
    }

    /**
     * Determine if a user is the one authenticated
     * @return boolean
     */
    public function getIsMeAttribute()
    {
        return $this->getAttributeFromArray('id') == auth()->id();
    }

    /**
     * Get the gravatar image link
     * @return string
     */
    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->getAttributeFromArray('email'))));
        return "https://secure.gravatar.com/avatar/{$hash}?d=identicon";
    }

    /**
     * Get the avatar image link
     * @return string
     */
    public function getAvatarAttribute()
    {
        $picture = $this->getAttributeFromArray('picture');
        return $picture ? (starts_with($picture, 'http') ? $picture : asset("uploads/{$picture}")) : $this->getGravatarAttribute();
    }

}
