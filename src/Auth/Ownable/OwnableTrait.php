<?php

namespace Ruysu\Core\Auth\Ownable;

use Illuminate\Database\Eloquent\Builder;

trait OwnableTrait
{

    /**
     * User that created this model
     * @return Relation
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Models created by the current user
     * @param  Builder $query
     * @return void
     */
    public function scopeMine(Builder $query)
    {
        $query->where('user_id', '=', auth()->id() ?: 0);
    }

    /**
     * Models created by a given user
     * @param  Builder $query
     * @param  integer $user_id
     * @return void
     */
    public function scopeBy(Builder $query, $user_id = null)
    {
        $query->where('user_id', '=', is_numeric($user_id) ? $user_id : auth()->id());
    }

    /**
     * Determine if a model instance was created by the current user
     * @return boolean
     */
    public function getIsMineAttribute()
    {
        $user_id = $this->getAttributeFromArray('user_id');

        return (int) $user_id == (int) auth()->id();
    }

    /**
     * Boot this trait
     * @return void
     */
    public static function bootOwnableTrait()
    {
        // define a static variable observe in onder to define whether to attach
        // listeners to the model
        if (!(isset(static::$observe) && static::$observe === false)) {
            static::observe(app('Ruysu\Core\Auth\Ownable\OwnableObserver'));
        }
    }

}
