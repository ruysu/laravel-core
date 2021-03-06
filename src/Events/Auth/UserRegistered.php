<?php

namespace Ruysu\Core\Events\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Ruysu\Core\Events\Event;

class UserRegistered extends Event
{

    /**
     * The newly created User
     * @var Authenticatable
     */
    protected $user;

    /**
     * The newly created users password
     * @var string
     */
    protected $password;

    /**
     * @param Authenticatable $user
     * @param string          $password
     */
    public function __construct(Authenticatable $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the user
     * @return Authenticatable
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the password
     * @return string
     */
    public function getPassword()
    {
        return $this->user;
    }

}
