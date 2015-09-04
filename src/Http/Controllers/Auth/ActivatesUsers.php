<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

trait ActivatesUsers
{

    /**
     * Activate a user by token
     * @param  string  $token
     * @param  Request $request
     * @param  Guard   $auth
     * @return Illuminate\Http\Response
     */
    public function getActivate($token, Request $request, Guard $auth)
    {
        try {
            $data = json_decode(app('encrypter')->decrypt($token));

            if (
                is_object($data) &&
                isset($data->id) &&
                is_numeric($data->id) &&
                isset($data->expires) &&
                with(new Carbon($data->expires))->gt(Carbon::now())
            ) {
                $this->activateUser($data->id);
                return $this->userWasActivated($data->id);
            } else {
                throw new DecryptException("Invalid token");
            }
        } catch (DecryptException $e) {
            return $this->userWasNotActivated();
        }
    }

    /**
     * Activate a user by id
     * @param  string  $id
     * @return boolean
     */
    protected function activateUser($id)
    {
        $user = $this->userById($id);
        $user->active = true;
        return $user->save();
    }

    /**
     * Send the response after a user was activated
     * @param  string $id
     * @return Illuminate\Http\Response
     */
    protected function userWasActivated($id)
    {
        return redirect()->route('auth.login')->with([
            'notice' => ['success', trans('core::auth.messages.activate.success')],
        ]);
    }

    /**
     * Send the response if a user failed to activate
     * @return Illuminate\Http\Response
     */
    protected function userWasNotActivated()
    {
        return redirect()->route('auth.login')->withErrors([
            'username' => trans('core::auth.messages.activate.error'),
        ]);
    }

    /**
     * Fetch a user by id
     * @param  string  $id
     * @return mixed
     */
    protected function userById($id)
    {
        return User::findOrFail($id);
    }

}
