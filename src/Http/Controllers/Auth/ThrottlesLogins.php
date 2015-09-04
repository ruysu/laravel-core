<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Cache\Store as Cache;
use Illuminate\Http\Request;

trait ThrottlesLogins
{

    /**
     * Determine if the user has too many failed login attempts.
     * @param  Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request, Cache $cache)
    {
        $attempts = $this->loginAttempts($request);
        $lockedOut = $cache->has($key = $this->loginLockExpirationKey($request));

        if ($attempts > $this->maxLoginAttempts() || $lockedOut) {
            if (!$lockedOut) {
                $minutes = round($this->lockOutTimemout() / 60);
                $cache->put($key, time() + ($minutes * 60), $minutes);
            }

            return true;
        }

        return false;
    }

    /**
     * Get the login attempts for the user.
     * @param  Request  $request
     * @return int
     */
    protected function loginAttempts(Request $request, Cache $cache)
    {
        return $cache->get($this->loginAttemptsKey($request)) ?: 0;
    }

    /**
     * Increment the login attempts for the user.
     * @param  Request  $request
     * @return int
     */
    protected function incrementLoginAttempts(Request $request, Cache $cache)
    {
        $cache->add($key = $this->loginAttemptsKey($request), 1, 1);
        return (int) $cache->increment($key);
    }

    /**
     * Redirect the user after determining they are locked out.
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request, Cache $cache)
    {
        $seconds = (int) $cache->get($this->loginLockExpirationKey($request)) - time();

        return redirect($this->loginUrl())
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => trans('passwords.throttle', ['seconds' => $seconds]),
            ]);
    }

    /**
     * Clear the login locks for the given user credentials.
     * @param  Request  $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request, Cache $cache)
    {
        $cache->forget($this->loginAttemptsKey($request));
        $cache->forget($this->loginLockExpirationKey($request));
    }

    /**
     * Get the login attempts cache key.
     * @param  Request  $request
     * @return string
     */
    protected function loginAttemptsKey(Request $request)
    {
        $username = $request->input('username');
        return 'login:attempts:' . md5($username . $request->ip());
    }

    /**
     * Get the login lock cache key.
     * @param  Request  $request
     * @return string
     */
    protected function loginLockExpirationKey(Request $request)
    {
        $username = $request->input('username');
        return 'login:expiration:' . md5($username . $request->ip());
    }

    /**
     * Get the maximum login attempt before lockdown
     * @return int
     */
    protected function maxLoginAttempts()
    {
        return property_exists($this, 'maxLoginAttempts') ? (int) $this->maxLoginAttempts : 5;
    }

    /**
     * Get the lockout timeout in minutes.
     * @return int|false
     */
    protected function lockOutTimemout()
    {
        return property_exists($this, 'lockOutTimemout') ? (int) $this->lockOutTimemout : 5;
    }

}
