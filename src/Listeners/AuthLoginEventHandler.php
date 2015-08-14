<?php

namespace Ruysu\Core\Listeners;

use DateTime;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;

class AuthLoginEventHandler
{

    /**
     * Auth driver
     * @var Guard
     */
    protected $auth;

    /**
     * Class constructor
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Actions to run upon login
     * @param  Authenticatable $user
     * @return void
     */
    public function handle(Authenticatable $user)
    {
        $user->last_login_at = $user->login_at;
        $user->login_at = new DateTime;

        $user->save();
    }

}
