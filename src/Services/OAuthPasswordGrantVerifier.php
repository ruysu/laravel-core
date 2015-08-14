<?php

namespace Ruysu\Core\Services;

use Illuminate\Contracts\Auth\Guard;

class OAuthPasswordGrantVerifier
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

    public function verify($username, $password)
    {
        $credentials = compact('password');

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $username;
        } else {
            $credentials['username'] = $username;
        }

        $credentials['active'] = 1;

        if ($this->auth->once($credentials)) {
            return $this->auth->id();
        }

        return false;
    }

}
