<?php

namespace Ruysu\Core\Jobs\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ruysu\Core\Jobs\Job;

class SendActivationEmail extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    /**
     * The user to wich send the activation link
     * @var Authenticatable
     */
    protected $user;

    /**
     * The app locale when the job was created
     * @var string
     */
    protected $locale;

    /**
     * Create a new job instance.
     * @param  Authenticatable $user
     * @param  string $locale
     * @return void
     */
    public function __construct(Authenticatable $user, $locale)
    {
        $this->user = $user;
        $this->locale = $locale;
    }

    /**
     * Execute the job.
     *
     * @param  Mailer  $mailer
     * @return void
     */
    public function handle(Mailer $mailer, Encrypter $encrypter)
    {
        app()->setLocale($this->locale);

        $token = $encrypter->encrypt(json_encode([
            'id' => $this->user->getKey(),
            'expires' => time() + (3600 * 72),
        ]));

        $user = $this->user;

        $mailer->send('core::emails.activate', compact('user', 'token'), function ($message) use ($user) {
            $message->to($user->email);
            $message->subject(trans('core::auth.emails.activate.subject'));
        });

    }

}
