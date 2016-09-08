<?php namespace ChaoticWave\Twister\Services;

use ChaoticWave\BlueVelvet\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TwitterService extends BaseService
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var \OAuth
     */
    protected $client;
    /**
     * @var bool
     */
    protected $forceSsl;
    /**
     * @var string
     */
    protected $userAgent;
    /**
     * @var array The available Twitter endpoints
     */
    protected $endpoints;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param \Illuminate\Contracts\Foundation\Application|\Laravel\Lumen\Application $app
     *
     * @throws \Exception
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->endpoints = \config('twister.endpoints', []);
        $this->forceSsl = \config('twister.force_ssl', true);
        $this->userAgent = \config('twister.user_agent', 'ChaoticWave Twister/1.0.0');
    }

    /**
     * Get a request_token from Twitter
     *
     * @param string $callback Optional callback to insert into the auth flow
     *
     * @return array|bool A hash with the token and secret. FALSE on failure
     * @throws \HttpException
     */
    public function getRequestToken($callback = null)
    {
        return $this->api('request_token', $callback ? ['oauth_callback' => $callback] : []);
    }

    /**
     * @param string|null $verifier
     *
     * @return array|bool A hash with the token and secret. FALSE on failure.
     */
    public function getAccessToken($verifier = null)
    {
        return $this->api('access_token', $verifier ? ['oauth_verifier' => $verifier] : []);
    }

    /**
     * Get the authorize URL
     *
     * @param array $token
     * @param bool  $login
     * @param bool  $force
     *
     * @return string
     */
    public function getAuthorizeUrl($token, $login = true, $force = false)
    {
        $_token = is_array($token) ? array_get($token, 'oauth_token') : $token;

        return $this->endpoints[$login ? 'authenticate' : 'authorize'] . '?oauth_token=' . $_token . '&force_login=' . ($force ? 'true' : 'false');
    }

    /**
     * @param string     $which
     * @param mixed|null $default
     *
     * @return string
     */
    protected function getEndpoint($which, $default = null)
    {
        return $this->client->url(array_get($this->endpoints, $which, $default));
    }

    /**
     * @param string $endpoint
     * @param array  $params
     * @param string $method
     *
     * @return array
     */
    protected function api($endpoint, $params = [], $method = Request::METHOD_GET)
    {
        $this->client->request($method, $this->getEndpoint($endpoint), $params);

        return $this->parseResponse($this->client->response);
    }

    /**
     * @param array $response
     * @param bool  $reset If true, resets the client's token
     *
     * @return bool
     * @throws \HttpException
     */
    protected function parseResponse($response, $reset = true)
    {
        if (!is_array($response)) {
            return false;
        }

        $_code = array_get($response, 'code');
        $_message = array_get($response, 'response');

        if (Response::HTTP_OK != $_code) {
            throw new \HttpException($_message, $_code);
        }

        parse_str($_message, $_token);

        if (isset($_token['oauth_token'], $_token['oauth_token_secret'])) {
            if ($reset) {
                $this->client->reconfigure(['token' => $_token['oauth_token'], 'secret' => $_token['oauth_token_secret']]);
            }

            return $_token;
        }

        return false;
    }
}
