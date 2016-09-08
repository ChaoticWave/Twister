<?php namespace ChaoticWave\Twister;

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
     * @param        $name
     * @param string $requestMethod
     * @param array  $_params
     * @param bool   $multipart
     *
     * @return array
     * @throws \Exception
     */
    public function query($name, $requestMethod = 'GET', $_params = [], $multipart = false)
    {
        $this->config['host'] = $this->tconfig['API_URL'];

        if ($multipart) {
            $this->config['host'] = $this->tconfig['UPLOAD_URL'];
        }

        $url = $this->client->url($this->tconfig['API_VERSION'] . '/' . $name);

        $this->client->user_request([
            'method'    => $requestMethod,
            'host'      => $name,
            'url'       => $url,
            'params'    => $_params,
            'multipart' => $multipart,
        ]);

        $response = $this->client->response;

        $format = 'object';

        if (isset($_params['format'])) {
            $format = $_params['format'];
        }

        $this->log('FORMAT : ' . $format);

        $this->error = $response['error'];

        if ($this->error) {
            $this->log('ERROR_CODE : ' . $response['errno']);
            $this->log('ERROR_MSG : ' . $response['error']);
        }

        if (isset($response['code']) && ($response['code'] < 200 || $response['code'] > 206)) {
            $_response = json_decode($response['response'], true);

            if (is_array($_response)) {
                if (array_key_exists('errors', $_response)) {
                    $error_code = $_response['errors'][0]['code'];
                    $error_msg = $_response['errors'][0]['message'];
                } else {
                    $error_code = $response['code'];
                    $error_msg = $response['error'];
                }
            } else {
                $error_code = $response['code'];
                $error_msg = ($error_code == 503) ? 'Service Unavailable' : 'Unknown error';
            }

            $this->log('ERROR_CODE : ' . $error_code);
            $this->log('ERROR_MSG : ' . $error_msg);

            throw new Exception('[' . $error_code . '] ' . $error_msg, $response['code']);
        }

        switch ($format) {
            default :
            case 'object' :
                $response = json_decode($response['response']);
                break;
            case 'json'   :
                $response = $response['response'];
                break;
            case 'array'  :
                $response = json_decode($response['response'], true);
                break;
        }

        return $response;
    }

    public function get($name, $_params = [], $multipart = false)
    {
        return $this->query($name, 'GET', $_params, $multipart);
    }

    public function post($name, $_params = [], $multipart = false)
    {
        return $this->query($name, 'POST', $_params, $multipart);
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
