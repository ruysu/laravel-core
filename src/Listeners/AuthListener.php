<?php

namespace Ruysu\Core\Listeners;

use DateTime;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ruysu\Core\Events\UserRegistered;

class AuthListener implements ShouldQueue
{

    /**
     * Mailer service
     * @var Mailer
     */
    protected $mailer;

    /**
     * Class constructor
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

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
            $this->dispatchWelcomeEmail($user);
        } else {
            $this->dispatchActivateEmail($user);
        }
    }

    /**
     * Dispatch the activation email
     * @param  Authenticatable $user
     * @return boolean
     */
    public function dispatchActivateEmail(Authenticatable $user)
    {
        $token = app('encrypter')->encrypt(json_encode([
            'id' => $user->id,
            'expires' => time() + (3600 * 72),
        ]));

        return $this->mailer->send(
            'core::emails.activate',
            compact('user', 'token'),
            function ($message) use ($user) {
                $message->to($user->email);
                $message->subject(trans('core::auth.emails.activate.subject'));
            }
        );
    }

    /**
     * Dispatch the activation email
     * @param  Authenticatable $user
     * @return boolean
     */
    public function dispatchWelcomeEmail(Authenticatable $user)
    {
        return $this->mailer->send(
            'core::emails.welcome',
            compact('user'),
            function ($message) use ($user) {
                $message->to($user->email);
                $message->subject(trans('core::auth.emails.welcome.subject'));
            }
        );
    }

}
