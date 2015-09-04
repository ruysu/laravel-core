<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Http\Request;
use Ruysu\Core\Events\Auth\UserActivated;

trait ActivatesUsers
{

    /**
     * Activate a user by token
     * @param  string  $token
     * @param  Request $request
     * @param  Events  $events
     * @return Illuminate\Http\Response
     */
    public function getActivate(
        Encrypter $encrypter,
        Request $request,
        Events $events,
        $token
    ) {
        try {
            $data = json_decode($encrypter->decrypt($token));

            if (
                is_object($data) &&
                isset($data->id) &&
                is_numeric($data->id) &&
                isset($data->expires) &&
                with(new Carbon($data->expires))->gt(Carbon::now())
            ) {
                $user = $this->activateUser($data->id);
                $events->fire(new UserActivated($user));
                return $this->userWasActivated($data->id);
            } else {
                throw new DecryptException("Invalid token");
            }
        } catch (Exception $e) {
            return $this->userWasNotActivated();
        }
    }

    /**
     * Activate a user by id
     * @param  string  $id
     * @return Authenticable
     */
    protected function activateUser($id)
    {
        $user = $this->userById($id);
        $user->active = true;
        $user->save();
        return $user;
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
