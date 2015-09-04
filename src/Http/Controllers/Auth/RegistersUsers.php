<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Ruysu\Core\Events\Auth\UserRegistered;
use Ruysu\Core\Http\Requests\Auth\AuthRequestInterface;

trait RegistersUsers
{

    /**
     * Show registration form
     * @return Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('core::auth.register');
    }

    /**
     * Register a new account
     * @param  AuthRequestInterface $request
     * @return Illuminate\Http\Response
     */
    public function postRegister(AuthRequestInterface $request, Events $events)
    {
        $activate = config('core.auth.activate', true);

        if ($user = $this->create($request->all(), !$activate)) {
            $events->fire(new UserRegistered($user, $request->get('password')));

            return $this->userWasRegistered($user);
        }

        return $this->userWasNotRegistered($user);
    }

    /**
     * Create a new user instance after a valid registration.
     * @param  array   $data
     * @param  boolean $active
     * @return User
     */
    protected function create(array $data, $active)
    {
        $user = new User($data);
        $user->password = bcrypt($data['password']);
        $user->active = $active;
        $user->save();
        return $user;
    }

    /**
     * Send the response when the user password was changed
     * @param  Authenticatable $user
     * @return Illuminate\Http\Response
     */
    protected function userWasRegistered(Authenticatable $user)
    {
        return redirect(action('\\' . __CLASS__ . '@getRegister'))
            ->with('notice', ['success', trans('core::auth.messages.register.success')]);
    }

    /**
     * Send the response when the user password was not changed
     * @return Illuminate\Http\Response
     */
    protected function userWasNotRegistered()
    {
        return redirect(action('\\' . __CLASS__ . '@getRegister'))
            ->with('notice', ['danger', trans('core::auth.messages.register.error')]);
    }

}
