<?php

namespace Ruysu\Core\Auth\Ownable;

use Illuminate\Database\Eloquent\Builder;

interface OwnableInterface
{

    /**
     * User that created this model
     * @return Relation
     */
    public function user();

    /**
     * Models created by the current user
     * @param  Builder $query
     * @return void
     */
    public function scopeMine(Builder $query);

    /**
     * Models created by a given user
     * @param  Builder $query
     * @param  integer $user_id
     * @return void
     */
    public function scopeBy(Builder $query, $user_id = null);

    /**
     * Determine if a model instance was created by the current user
     * @return boolean
     */
    public function getIsMineAttribute();

    /**
     * Boot this trait
     * @return void
     */
    public static function bootOwnableTrait();

}
