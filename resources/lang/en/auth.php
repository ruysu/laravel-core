<?php

return array(

    'labels' => array(
        'username' => 'Username',
        'username-email' => 'Username or Email',
        'email' => 'E-mail',
        'name' => 'Name',
        'picture' => 'Profile Picture',
        'roles' => 'Roles',
        'password' => 'Password',
        'password_confirmation' => 'Confirm your password',
        'new-password' => 'New password',
        'new-password_confirmation' => 'Confirm your new password',
        'register' => 'Sign up',
        'login' => 'Sign in',
        'remember-me' => 'Remember me',
        'logout' => 'Sign out',
        'my-account' => 'My account',
        'reset-password' => 'Reset password',
        'change-password' => 'Change password',
        'current-password' => 'Current password',
        'update' => 'Update',
        'send' => 'Send',
        'submit' => 'Submit',
        'forgot-password' => 'Forgot your password?',
        'no-account' => 'Don\'t have an account?',
        'have-account' => 'Already have an account?',
        'or-sign-in' => 'Or sign-in with',
    ),

    'messages' => array(
        'login' => array(
            'error' => 'E-mail or password missmatch.',
            'success' => 'You have successfully logged in.',
            'not-activated' => 'You haven\'t activated your account yet',
        ),
        'activate' => array(
            'success' => 'Your account has been activated, you can now sign in.',
        ),
        'register' => array(
            'error' => 'Something went wrong, please try again later.',
            'success' => 'You have successfully signed up, we sent an e-mail with the steps to activate your account.',
        ),
        'account' => array(
            'error' => 'Something went wrong, please try again later (yout account information has not been changed).',
            'success' => 'You have updated your account information.',
        ),
        'password' => array(
            'error' => 'Something went wrong, please try again later, your password was not updated.',
            'success' => 'You have updated your account information.',
        ),
        'remind' => array(
            'error' => 'Something went wrong, please try again later.',
            'success' => 'You may reset your password by following the steps described on the e-mail we just sent you.',
        ),
        'reset' => array(
            'error' => 'Something went wrong, please try again later.',
            'success' => 'You may now use your new password to sign in.',
        ),
        'change_password' => 'Change password',
        'account_info' => 'Account information',
    ),

    'emails' => array(
        'remind' => array(
            'subject' => 'Password Reminder',
            'title' => 'Reset your password',
            'body' => 'To reset your password, complete this form:',
            'body-expire' => 'This link expires in :minutes minutes',
        ),
        'activate' => array(
            'subject' => 'Activate your account',
            'title' => 'Activate your account',
            'body' => 'Your account has been created, in order to activate it please access this link:',
        ),
        'welcome' => array(
            'subject' => 'Your new account',
            'title' => 'Welcome',
            'body' => 'Your account has been created, you may sign in on this url:',
            'password' => 'A temporary password has been generated, it is strongly recomended that you change it as soon as possible.',
        ),
    ),

);
