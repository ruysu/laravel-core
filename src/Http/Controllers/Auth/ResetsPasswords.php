<?php

namespace Ruysu\Core\Http\Controllers\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Ruysu\Core\Http\Requests\Auth\AuthRequestInterface;

trait ResetsPasswords
{

    /**
     * Show password recovery form
     * @return Illuminate\Http\Response
     */
    public function getRemind()
    {
        return view('core::auth.remind');
    }

    /**
     * Send an email to user if exists with reset instructions
     * @param  AuthRequestInterface $request
     * @param  PasswordBroker       $passwords
     * @return Illuminate\Http\Response
     */
    public function postRemind(AuthRequestInterface $request, PasswordBroker $passwords)
    {
        $response = $passwords->sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(trans('core::auth.emails.remind.subject'));
        });

        switch ($response) {
            case PasswordBroker::RESET_LINK_SENT:
                return $this->reminderWasSent($request);
                break;
            case PasswordBroker::INVALID_USER:
                return $this->reminderWasNotSent($request, $response);
                break;
        }
    }

    /**
     * Show the password reset form
     * @param  string $token
     * @return Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('core::auth.reset', compact('token'));
    }

    /**
     * Reset a users password
     * @param  AuthRequestInterface $request
     * @param  PasswordBroker       $passwords
     * @return Illuminate\Http\Response
     */
    public function postReset(AuthRequestInterface $request, PasswordBroker $passwords)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = $passwords->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case PasswordBroker::PASSWORD_RESET:
                return $this->passwordWasReset($request);
                break;
            default:
                return $this->passwordWasNotReset($request, $response);
                break;
        }
    }

    /**
     * Reset the given user's password.
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
    }

    /**
     * Handle response when reminder was sent
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    protected function reminderWasSent(Request $request)
    {
        return redirect(action('\\' . __CLASS__ . '@getRemind'))
            ->with('notice', ['success', trans('core::auth.messages.remind.success')]);
    }

    /**
     * Handle response when reminder was not sent
     * @param Request $request
     * @param string $response
     * @return Illuminate\Http\Response
     */
    protected function reminderWasNotSent(Request $request, $response)
    {
        return redirect(action('\\' . __CLASS__ . '@getRemind'))
            ->withErrors(['email' => trans($response)])
            ->with('notice', ['danger', trans('core::auth.messages.remind.error')]);
    }

    /**
     * Handle response when reminder was sent
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    protected function passwordWasReset(Request $request)
    {
        return redirect()->route('auth.login')
            ->with('notice', ['success', trans('core::auth.messages.reset.success')]);
    }

    /**
     * Handle response when reminder was not sent
     * @param Request $request
     * @param string $response
     * @return Illuminate\Http\Response
     */
    protected function passwordWasNotReset(Request $request, $response)
    {
        return redirect(action('\\' . __CLASS__ . '@getReset', [$request->get('token')]))
            ->withErrors(['email' => trans($response)])
            ->with('notice', ['danger', trans('core::auth.messages.reset.error')]);
    }

}
