<?php

namespace Ruysu\Core\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Support\Fluent;

class AuthMailer
{

    /**
     * Mailer instance
     * @var Mailer
     */
    protected $mailer;

    /**
     * Queue instance
     * @var Queue
     */
    protected $queue;

    /**
     * Encrypter instance
     * @var Encrypter
     */
    protected $crypt;

    /**
     * @param Mailer   $mailer
     */
    public function __construct(Mailer $mailer, Queue $queue, Encrypter $crypt)
    {
        $this->mailer = $mailer;
        $this->queue = $queue;
        $this->crypt = $crypt;
    }

    /**
     * Send welcome e-mail to given user
     * @param  Authenticatable $user
     * @return void
     */
    public function sendActivate(Authenticatable $user)
    {
        $token = $this->crypt->encrypt($user->email);

        $this->queue->push(__CLASS__ . '@dispatchActivate', compact('user', 'token'));
    }

    /**
     * Send welcome e-mail to given user
     * @param  object $job
     * @param  array  $data
     * @return void
     */
    public function dispatchActivate($job, $data)
    {
        $job->delete();

        extract($data);

        if (!is_object($user)) {
            $user = new Fluent($user);
        }

        $this->mailer->send('emails.auth.activate', compact('user', 'token'), function ($message) use ($user) {
            $message->to($user->email)->subject(trans('auth.emails.activate.subject'));
        });
    }

}
