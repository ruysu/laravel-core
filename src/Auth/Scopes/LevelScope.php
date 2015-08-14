<?php

namespace Ruysu\Core\Auth\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class LevelScope implements ScopeInterface
{

    /**
     * The user level required
     * @var int
     */
    protected $level;

    public function __construct($level)
    {
        $this->level = $level;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $builder->whereRaw("level = {$this->level}");
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function remove(Builder $builder)
    {
    }

}
