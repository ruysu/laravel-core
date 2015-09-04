<?php

namespace Ruysu\Core\Listeners;

use DateTime;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Ruysu\Core\Events\UserRegistered;
use Ruysu\Core\Jobs\Auth\SendActivationEmail;
use Ruysu\Core\Jobs\Auth\SendWelcomeEmail;

class AuthListener
{

    use DispatchesJobs;

    /**
     * Actions to run upon login
     * @param  Authenticatable $user
     * @return void
     */
    public function onLogin(Authenticatable $user)
    {
        $user->last_login_at = $user->login_at;
        $user->login_at = new DateTime;
        $user->save();
    }

    /**
     * Actions to run upon registration
     * @param  UserRegistered $event
     * @return void
     */
    public function onRegister(UserRegistered $event)
    {
        $user = $event->getUser();

        if ($user->active) {
            $this->dispatch(new SendWelcomeEmail($user));
        } else {
            $this->dispatch(new SendActivationEmail($user));
        }
    }

    /**
     * Actions to run upon activation
     * @param  UserActivated $event
     * @return void
     */
    public function onActivate(UserActivated $event)
    {
        $user = $event->getUser();
        $this->dispatch(new SendWelcomeEmail($user));
    }

}
