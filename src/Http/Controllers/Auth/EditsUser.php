<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Ruysu\Core\Http\Requests\Auth\AuthRequestInterface;

trait EditsUser
{

    /**
     * Show user edit form
     * @return Illuminate\Http\Response
     */
    public function getEdit(Guard $auth)
    {
        $user = $auth->user();
        return view('core::auth.edit', compact('user'));
    }

    /**
     * Change current user's information
     * @param  AuthRequestInterface $request
     * @param  Guard                $guard
     * @return Illuminate\Http\Response
     */
    public function postEdit(AuthRequestInterface $request, Guard $auth)
    {
        if ($user = $auth->user()) {
            if ($this->updateUser($request, $user)) {
                return $this->userWasUpdated($user);
            }

            return $this->userWasNotUpdated($user);
        }

        return redirect(route('auth.login'));
    }

    /**
     * Change the password for a given user
     * @param  Request          $request
     * @param  Authenticatable  $user
     * @return Illuminate\Http\Response
     */
    protected function updateUser(Request $request, Authenticatable $user)
    {
        $user->fill($request->all());
        return $user->save();
    }

    /**
     * Send the response when the user password was changed
     * @param  Authenticatable $user
     * @return Illuminate\Http\Response
     */
    protected function userWasUpdated(Authenticatable $user)
    {
        return redirect(action('\\' . __CLASS__ . '@getEdit'))
            ->with('notice', ['success', trans('core::auth.messages.account.success')]);
    }

    /**
     * Send the response when the user password was not changed
     * @return Illuminate\Http\Response
     */
    protected function userWasNotUpdated()
    {
        return redirect(action('\\' . __CLASS__ . '@getEdit'))
            ->with('notice', ['danger', trans('core::auth.messages.account.error')]);
    }

}
