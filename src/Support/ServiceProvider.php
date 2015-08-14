<?php

namespace Ruysu\Core\Support;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

abstract class ServiceProvider extends LaravelServiceProvider
{

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
    }

}
