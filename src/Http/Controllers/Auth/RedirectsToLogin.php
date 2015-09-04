<?php

namespace Ruysu\Core\Http\Controllers\Auth;

trait RedirectsToLogin
{

    /**
     * Send the response when the user is not authenticated
     * @return Illuminate\Http\Response
     */
    protected function userNotLoggedIn()
    {
        return redirect(route('auth.login'));
    }

}
