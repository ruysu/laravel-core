<?php

namespace Ruysu\Core\Eloquent;

use Illuminate\Database\Eloquent\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

class Collection extends BaseCollection
{

    /**
     * Load a has many relation count
     * @param  string $relation
     * @return void
     */
    public function loadRelatedCount($relation)
    {
        if (count($this->items) > 0) {
            $model = $this->first();
            $property_name = "{$relation}_count";
            $relation = $model->$relation();
            $related = $relation->getRelated();

            if ($relation instanceof BelongsToMany) {
                $parent_key = last(explode('.', $relation->getForeignKey()));
                $query = $relation->newPivotStatement();
            } else {
                $parent_key = $relation->getPlainForeignKey();
                $query = $related->newQuery();

                if ($relation instanceof MorphOneOrMany) {
                    $query->where($relation->getPlainMorphType(), $relation->getMorphClass());
                }
            }

            $query->whereIn($parent_key, $this->lists($model->getKeyName()))
                ->selectRaw($parent_key . ', count(*) as relation_count')
                ->groupBy($parent_key);

            $results = $query->lists('relation_count', $parent_key);

            $this->each(function ($model) use ($results, $property_name) {
                if (isset($results[$model->id])) {
                    $model->$property_name = $results[$model->id];
                } else {
                    $model->$property_name = 0;
                }
            });
        }
    }

    /**
     * Check if any of the related model for a given relation, belongs to the
     * current user
     * @param  string $relation
     * @return void
     */
    public function loadInMyRelated($relation)
    {
        $auth = app('auth');
        $user = $auth->user();
        $user_id = $auth->id();

        if (count($this->items) > 0 && $user_id) {
            $model = $this->first();
            $property_name = "in_my_{$relation}";
            $relation = $user->$relation();
            $related = $relation->getRelated();

            if ($relation instanceof BelongsToMany || $relation instanceof MorphToMany) {
                $parent_key = last(explode('.', $relation->getOtherKey()));
                $query = $relation->newPivotStatement();

                if ($relation instanceof MorphToMany) {
                    $query->where($relation->getPlainMorphType(), $relation->getMorphClass());
                }
            } else {
                $parent_key = $relation->getPlainForeignKey();
                $query = $related->newQuery();

                if ($relation instanceof MorphOneOrMany) {
                    $query->where($relation->getPlainMorphType(), $relation->getMorphClass());
                }
            }

            $query->where('user_id', '=', $user->getKey())
                ->whereIn($parent_key, $this->lists($model->getKeyName()))
                ->selectRaw($parent_key . ', count(*) as relation_count')
                ->groupBy($parent_key);

            $results = $query->lists('relation_count', $parent_key);

            $this->each(function ($model) use ($results, $property_name) {
                if (isset($results[$model->id])) {
                    $model->$property_name = $results[$model->id];
                } else {
                    $model->$property_name = 0;
                }
            });
        }
    }

}
