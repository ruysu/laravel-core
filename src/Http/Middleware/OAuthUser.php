<?php

namespace Ruysu\Core\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use LucaDegasperi\OAuth2Server\Authorizer;
use LucaDegasperi\OAuth2Server\Middleware\OAuthMiddleware;

class OAuthUser extends OAuthMiddleware
{

    /**
     * The Guard implementation.
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth, Authorizer $authorizer, $httpHeadersOnly = false)
    {
        $this->auth = $auth;
        parent::__construct($authorizer, $httpHeadersOnly);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $scopesString = null)
    {
        $scopes = [];

        if (!is_null($scopesString)) {
            $scopes = explode('+', $scopesString);
        }

        try {
            $this->authorizer->validateAccessToken($this->httpHeadersOnly);
            $this->validateScopes($scopes);

            if ($this->authorizer->getResourceOwnerType() == 'user') {
                $this->auth->onceUsingId($this->authorizer->getResourceOwnerId());
            }
        } catch (Exception $e) {
        }

        return $next($request);
    }

}
