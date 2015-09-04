<?php

namespace Ruysu\Core\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

abstract class EventServiceProvider extends ServiceProvider
{

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $events->listen(
            'auth.login',
            'Ruysu\Core\Listeners\AuthListener@onLogin'
        );

        $events->listen(
            'Ruysu\Core\Events\Auth\UserRegistered',
            'Ruysu\Core\Listeners\AuthListener@onRegister'
        );

        $events->listen(
            'Ruysu\Core\Events\Auth\UserActivated',
            'Ruysu\Core\Listeners\AuthListener@onActivate'
        );
    }

}
