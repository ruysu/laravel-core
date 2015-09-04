<?php

namespace Ruysu\Core\Events\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Ruysu\Core\Events\Event;

class UserActivated extends Event
{

    /**
     * The newly created User
     * @var Authenticatable
     */
    protected $user;

    /**
     * @param Authenticatable $user
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * Get the user
     * @return Authenticatable
     */
    public function getUser()
    {
        return $this->user;
    }

}
