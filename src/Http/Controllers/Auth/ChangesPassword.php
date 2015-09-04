<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Ruysu\Core\Http\Requests\Auth\AuthRequestInterface;

trait ChangesPassword
{

    /**
     * Show change password form
     * @return Illuminate\Http\Response
     */
    public function getPassword()
    {
        return view('core::auth.password');
    }

    /**
     * Change current user's password
     * @param  AuthRequestInterface $request
     * @param  Guard                $auth
     * @return Illuminate\Http\Response
     */
    public function postPassword(AuthRequestInterface $request, Guard $auth)
    {
        if ($user = $auth->user()) {
            if (!$this->checkPassword($user, $request->get('current_password'))) {
                return $this->passwordDidNotMatch($user);
            }

            if ($this->changePassword($request, $user)) {
                return $this->passwordWasChanged($user);
            }

            return $this->passwordWasNotChanged($user);
        }

        return redirect(route('auth.login'));
    }

    /**
     * Change the password for a given user
     * @param  Request          $request
     * @param  Authenticatable  $user
     * @return boolean
     */
    protected function changePassword(Request $request, Authenticatable $user)
    {
        $user->password = bcrypt($request->get('password'));
        return $user->save();
    }

    /**
     * Check if passwords match
     * @param  Authenticatable $user
     * @param  string          $password
     * @return boolean
     */
    protected function checkPassword(Authenticatable $user, $password)
    {
        return app('hash')->check($password, $user->getAuthPassword());
    }

    /**
     * Send the response when the user password was changed
     * @param  Authenticatable $user
     * @return Illuminate\Http\Response
     */
    protected function passwordWasChanged(Authenticatable $user)
    {
        return redirect(action('\\' . __CLASS__ . '@getPassword'))
            ->with('notice', ['success', trans('core::auth.messages.password.success')]);
    }

    /**
     * Send the response when the user password was not changed
     * @param  Authenticatable $user
     * @return Illuminate\Http\Response
     */
    protected function passwordWasNotChanged(Authenticatable $user)
    {
        return redirect(action('\\' . __CLASS__ . '@getPassword'))
            ->with('notice', ['danger', trans('core::auth.messages.password.error')]);
    }

    /**
     * Send the response when the user password was not changed
     * @param  Authenticatable $user
     * @return Illuminate\Http\Response
     */
    protected function passwordDidNotMatch(Authenticatable $user)
    {
        return redirect(action('\\' . __CLASS__ . '@getPassword'))
            ->with('notice', ['danger', trans('core::auth.messages.password.error-match')]);
    }

}
