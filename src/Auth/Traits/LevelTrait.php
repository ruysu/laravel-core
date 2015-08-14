<?php

namespace Ruysu\Core\Auth\Traits;

trait LevelTrait
{

    /**
     * The table where the models are stored
     * @var string
     */
    protected $table = 'users';

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
