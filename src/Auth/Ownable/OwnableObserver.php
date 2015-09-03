<?php

namespace Ruysu\Core\Auth\Ownable;

use Illuminate\Contracts\Auth\Guard;

class OwnableObserver
{

    /**
     * The auth driver
     * @var Guard
     */
    protected $auth;

    /**
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Fired when creating a Model
     * @param  Model        $model
     * @return void|boolean
     */
    public function creating(OwnableInterface $model)
    {
        if (!$model->user_id) {
            $user = $this->auth->user();

            if (!$user) {
                return false;
            }

            $model->user_id = $user->id;
        }
    }

    /**
     * Fired when updating a Model
     * @param  Model        $model
     * @return void|boolean
     */
    public function updating(OwnableInterface $model)
    {
        $user = $this->auth->user();
        $is_admin = $user && $user->is_admin;

        if (!$is_admin && !($user && $user->id == $model->user_id)) {
            return false;
        }
    }

    /**
     * Fired when deleting a Model
     * @param  Model        $model
     * @return void|boolean
     */
    public function deleting(OwnableInterface $model)
    {
        $user = $this->auth->user();
        $is_admin = $user && $user->is_admin;

        if (!$is_admin && !($user && $user->id == $model->user_id)) {
            return false;
        }
    }

}
