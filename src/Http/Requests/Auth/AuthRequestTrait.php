<?php

namespace Ruysu\Core\Http\Requests\Auth;

trait AuthRequestTrait
{

    /**
     * Rules used for the login validation
     * @return array
     */
    public function rulesForLogin()
    {
        $this->merge = false;

        return [
            'username' => ['required'],
            'password' => $this->passwordRule(true, false),
        ];
    }

    /**
     * Rules used for the register account validation
     * @return array
     */
    public function rulesForRegister()
    {
        return [
            'name' => ['required'],
            'email' => $this->emailRule(true, true),
            'username' => $this->usernameRule(true, true),
            'password' => $this->passwordRule(true, true),
        ];
    }

    /**
     * Rules used for the edit account validation
     * @return array
     */
    public function rulesForEdit()
    {
        return [
            'name' => ['required'],
            'picture' => ['image'],
            'email' => $this->emailRule(true, true),
            'username' => $this->usernameRule(true, true),
        ];
    }

    /**
     * Rules used for the change password validation
     * @return array
     */
    public function rulesForPassword()
    {
        $this->merge = false;

        return [
            'current_password' => $this->passwordRule(true, false),
            'password' => $this->passwordRule(true, true),
        ];
    }

    /**
     * Rules used for the reminder request validation
     * @return array
     */
    public function rulesForRemind()
    {
        $this->merge = false;

        return [
            'email' => $this->emailRule(true, false),
        ];
    }

    /**
     * Rules used for the password reset validation
     * @return array
     */
    public function rulesForReset()
    {
        $this->merge = false;

        return [
            'token' => ['required'],
            'password' => $this->passwordRule(true, true),
        ];
    }

    /**
     * Prepare the validator before its been resolved
     * @return void
     */
    protected function prepare()
    {
        if ($user_id = auth()->id()) {
            $this->replaceValue('key', $user_id);
        }
    }

    /**
     * Construct the validation rule for the email field.
     * @param  boolean  $required
     * @param  boolean|null  $merge
     * @return array
     */
    protected function emailRule($required = true, $unique = true)
    {
        $rules = ['email'];

        $required && $rules[] = 'required';
        $unique && $rules[] = $this->unique('email', 'users');

        return $rules;
    }

    /**
     * Construct the validation rule for the identifier field.
     * @param  boolean  $required
     * @param  boolean|null  $merge
     * @return array
     */
    protected function usernameRule($required = true, $unique = true)
    {
        $rules = ['alpha_num', 'min:5', 'max:30'];

        $required && $rules[] = 'required';
        $unique && $rules[] = $this->unique('username', 'users');

        return $rules;
    }

    /**
     * Generate a uniform password rule
     * @param  boolean $confirmed
     * @param  boolean $required
     * @return array
     */
    protected function passwordRule($required = false, $confirmed = false)
    {
        $rules = ['min:4'];

        if ($required) {
            $rules[] = 'required';
        }

        if ($confirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }

}
