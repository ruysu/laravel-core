<?php

namespace Ruysu\Core\Auth\Traits;

use Ruysu\Core\Auth\Scopes\LevelScope;

trait LevelTrait
{

    /**
     * Boot this trait
     * @return void
     */
    public static function bootLevelTrait()
    {
        static::creating(function ($model) {
            $model->level = static::$user_level;
        });

        static::addGlobalScope(new LevelScope(static::$user_level));
    }

}
