<?php

namespace Ruysu\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use LucaDegasperi\OAuth2Server\Authorizer as OAuth;

class AuthController extends Controller
{

    /**
     * The current request instance
     * @var Request
     */
    protected $request;

    /**
     * The params used for
     * @var Fluent
     */
    protected $params;

    /**
     * The oauth authorizer
     * @var OAuth
     */
    protected $oauth;

    /**
     * @param Request $request
     */
    public function __construct(Request $request, OAuth $oauth)
    {
        $this->request = $request;
        $this->oauth = $oauth;
        $this->params = new Fluent([
            'client_id' => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
        ]);
    }

    /**
     * Authenticate a user
     * @return Response
     */
    public function postLogin()
    {
        $this->params->username = $this->request->get('username');
        $this->params->password = $this->request->get('password');
        $this->params->grant_type = 'password';

        return $this->sendAuthorization();
    }

    /**
     * Refresh a token
     * @return Response
     */
    public function postRefresh()
    {
        $this->params->refresh_token = $this->request->get('refresh_token');
        $this->params->grant_type = 'refresh_token';

        return $this->sendAuthorization();
    }

    /**
     * Get the current user info
     * @return Response
     */
    public function getUserInfo()
    {
        if ($user = auth()->user()) {
            return auth()->user();
        }

        return response()->json(['error' => 'not_authenticated'], 400);
    }

    /**
     * Send the request after setting authorization params
     * @return Response
     */
    public function sendAuthorization()
    {
        $this->request->merge($this->params->toArray());
        $this->oauth->getIssuer()->setRequest($this->request);
        $token = $this->oauth->issueAccessToken();

        if (auth()->check()) {
            $token['user'] = auth()->user();
        }

        return response()->json($token);
    }

}
