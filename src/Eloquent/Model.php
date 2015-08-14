<?php

namespace Ruysu\Core\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{

    /**
     * Return a custom collection
     * @param  array  $models
     * @return Collection
     */
    public function newCollection(array $models = array())
    {
        return new Collection($models);
    }

    /**
     * Format a number in the fashion of 1.5k or 1.5M
     * @param  string $value
     * @return string
     */
    protected function asCounter($value)
    {
        $prepend = $value < 0 ? '-' : '';

        $value = abs((int) $value);

        if ($value < 1000) {
            return $prepend . $value;
        }

        $decimal = $value < 1000000 ? 1000 : 1000000;

        $format = round($value / $decimal, 1);

        return $prepend . number_format($format, $format - intval($format) ? 1 : 0, '.', ',') . ($decimal == 1000 ? 'k' : 'M');
    }

    /**
     * Get a formatted, translated & readable date from created_at timestamp
     * @return string
     */
    public function getCreatedAtHumanAttribute()
    {
        $created_at = $this->getAttribute('created_at');
    }

    /**
     * Get a formatted, translated & readable date from updated_at timestamp
     * @return string
     */
    public function getUpdatedAtHumanAttribute()
    {
        $updated_at = $this->getAttribute('updated_at');
    }

    /**
     * Get time ellapsed from created_at timestamp
     * @return string
     */
    public function getCreatedAtAgoAttribute()
    {
        $created_at = $this->getAttribute('created_at');
        return Carbon::now()->diffForHumans($created_at);
    }

    /**
     * Get time ellapsed from updated_at timestamp
     * @return string
     */
    public function getUpdatedAtAgoAttribute()
    {
        $updated_at = $this->getAttribute('updated_at');
        return Carbon::now()->diffForHumans($updated_at);
    }

    /**
     * Get unix timestamp from created_at timestamp
     * @return int
     */
    public function getCreatedAtTimeAttribute()
    {
        $created_at = $this->getAttribute('created_at');
        return $created_at->getTimestamp();
    }

    /**
     * Get unix timestamp from updated_at timestamp
     * @return int
     */
    public function getUpdatedAtTimeAttribute()
    {
        $updated_at = $this->getAttribute('updated_at');
        return $updated_at->getTimestamp();
    }

    /**
     * Exclude IDs from query
     * @param  Builder $query
     * @param  array  $ids
     * @return void
     */
    public function scopeExceptIds($query, array $ids)
    {
        $query->whereNotIn($this->getKeyName(), $ids);
    }

    /**
     * Exclude IDs that are not in a given array
     * @param  Builder $query
     * @param  array  $ids
     * @return void
     */
    public function scopeWithIds($query, array $ids)
    {
        $query->whereIn($this->getKeyName(), $ids);
    }

}
