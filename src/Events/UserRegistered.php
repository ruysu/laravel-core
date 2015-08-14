<?php

namespace Ruysu\Core\Events;

use Illuminate\Contracts\Auth\Authenticatable;

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

}
