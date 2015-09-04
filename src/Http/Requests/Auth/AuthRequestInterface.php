<?php

namespace Ruysu\Core\Http\Requests\Auth;

interface AuthRequestInterface
{

    /**
     * Rules used for the login validation
     * @return array
     */
    public function rulesForLogin();

    /**
     * Rules used for the register account validation
     * @return array
     */
    public function rulesForRegister();

    /**
     * Rules used for the edit account validation
     * @return array
     */
    public function rulesForEdit();

    /**
     * Rules used for the change password validation
     * @return array
     */
    public function rulesForPassword();

    /**
     * Rules used for the reminder request validation
     * @return array
     */
    public function rulesForRemind();

    /**
     * Rules used for the password reset validation
     * @return array
     */
    public function rulesForReset();

}
