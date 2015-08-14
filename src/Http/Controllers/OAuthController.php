<?php

namespace Ruysu\Core\Http\Controllers;

use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Authorizer as OAuth;

class OAuthController extends Controller
{

    /**
     * The OAuth Server
     * @var OAuth
     */
    protected $oauth;

    /**
     * @param Oauth $oauth
     */
    public function __construct(Oauth $oauth)
    {
        $this->oauth = $oauth;
    }

    /**
     * Get an access Token
     * @return Response
     */
    public function postAccessToken()
    {
        return response()->json($this->oauth->issueAccessToken());
    }

    /**
     * Validate an access token
     * @param  Request $request
     * @return Response
     */
    public function postValidateAccessToken(Request $request)
    {
        return response()->json($this->oauth->getChecker()->isValidRequest(false, $request->get('access_token')));
    }

}
