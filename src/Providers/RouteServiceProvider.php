<?php

namespace Ruysu\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

abstract class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middleware('oauth', 'LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware');
        $router->middleware('oauth-user', 'Ruysu\Core\Http\Middleware\OAuthUser');
        $router->middleware('oauth-owner', 'LucaDegasperi\OAuth2Server\Middleware\OAuthOwnerMiddleware');
        $router->middleware('check-authorization-params', 'LucaDegasperi\OAuth2Server\Middleware\CheckAuthCodeRequestMiddleware');

        parent::boot($router);
    }

}
