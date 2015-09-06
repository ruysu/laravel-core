<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Ruysu\Core\Http\Requests\Auth\AuthRequestInterface;

trait AuthenticatesUsers
{

    /**
     * Show login form
     * @return Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('core::auth.login');
    }

    /**
     * Perform login attempt
     * @param  AuthRequestInterface $request
     * @param  Guard                $auth
     * @return Illuminate\Http\Response
     */
    public function postLogin(AuthRequestInterface $request, Guard $auth)
    {
        if (($throttles = $this->throttlesLogins()) && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->only('password');
        $username = $request->get('username');

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $username;
        } else {
            $credentials['username'] = $username;
        }

        if ($auth->once($credentials)) {
            $user = $auth->user();

            if ($throttles) {
                $this->clearLoginAttempts($request);
            }

            if (!$user->active) {
                return $this->handleUserIsNotActive($request, $user);
            }

            $this->authenticateUser($auth, $user);

            return $this->handleUserWasAuthenticated($request, $user);
        }

        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return $this->handleUserWasNotAuthenticated($request);
    }

    /**
     * Log the authenticated user out
     * @param  Request $request
     * @param  Guard   $auth
     * @return Illuminate\Http\Response
     */
    public function getLogout(Request $request, Guard $auth)
    {
        $auth->logout();
        return $this->handleUserWasLoggedOut($request);
    }

    /**
     * Send the response after the user was authenticated.
     * @param  Request         $request
     * @param  Authenticatable $user
     * @return Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, Authenticatable $user)
    {

        return redirect()->intended('/');
    }

    /**
     * Send the response after the user was authenticated but account was
     * not yet activated.
     * @param  Request         $request
     * @param  Authenticatable $user
     * @return Illuminate\Http\Response
     */
    protected function handleUserIsNotActive(Request $request, Authenticatable $user)
    {
        return redirect($this->loginUrl())
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => trans('core::auth.messages.login.not-activated'),
            ]);
    }

    /**
     * Send the response after the user failed to be authenticated.
     * @param  Request  $request
     * @return Illuminate\Http\Response
     */
    protected function handleUserWasNotAuthenticated(Request $request)
    {
        return redirect($this->loginUrl())
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => trans('core::auth.messages.login.error'),
            ]);
    }

    /**
     * Send the response after the user was logged out of the app
     * @param  Request $request
     * @return Respose
     */
    protected function handleUserWasLoggedOut(Request $request)
    {
        return redirect('/');
    }

    /**
     * Authenticate user
     * @param  Guard           $auth
     * @param  Authenticatable $user
     * @param  boolean         $remember
     * @return Illuminate\Http\Response
     */
    protected function authenticateUser(Guard $auth, Authenticatable $user, $remember = false)
    {
        $auth->login($user, $remember);
    }

    /**
     * Get the path to the login route.
     * @return string
     */
    protected function loginUrl()
    {
        return $this->url('getLogin');
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     * @return bool
     */
    protected function throttlesLogins()
    {
        return in_array(
            'Ruysu\Core\Auth\Controllers\ThrottlesLogins', class_uses_recursive(get_class($this))
        );
    }

}
