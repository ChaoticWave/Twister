<?php namespace ChaoticWave\Twister\Services;

use ChaoticWave\BlueVelvet\Services\BaseService;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Http\Uri\UriFactoryInterface;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Service\ServiceInterface;
use OAuth\Common\Storage\Session;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\OAuth1\Service\Twitter;
use OAuth\ServiceFactory;

class TwisterService extends BaseService
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @var string The client's service name
     */
    const CLIENT_SERVICE_NAME = 'twitter';

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var Twitter
     */
    protected $client;
    /**
     * @var CredentialsInterface
     */
    protected $credentials;
    /**
     * @var array The available Twitter endpoints
     */
    protected $endpoints;
    /**
     * @var TokenStorageInterface
     */
    protected $store;
    /**
     * @var UriFactoryInterface
     */
    protected $uris;
    /**
     * @var ServiceInterface|\OAuth\OAuth1\Service\ServiceInterface|\OAuth\OAuth2\Service\ServiceInterface
     */
    protected $services;
    /**
     * @var UriInterface
     */
    protected $currentUri;

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
        $this->store = new Session(true, 'twister-oauth-token', 'twister-oauth-state');

        $this->services = new ServiceFactory();
        $this->uris = new UriFactory();
        $this->currentUri = $this->uris->createFromSuperGlobalArray($_SERVER);
        $this->currentUri->setQuery('');

        $this->credentials = new Credentials(
            config('twister.secrets.consumer_key'),
            config('twister.secrets.consumer_secret'),
            $this->currentUri->getAbsoluteUri()
        );

        $this->client = $this->services->createService(static::CLIENT_SERVICE_NAME, $this->credentials, $this->store);

        $this->checkForInteractiveRequest();
    }

    /**
     * @return array
     */
    public function getUserTimeline()
    {
        return json_decode($this->client->request('statuses/user_timeline.json'));
    }

    protected function checkForInteractiveRequest()
    {
        if (!empty($_GET['oauth_token'])) {
            $_token = $this->store->retrieveAccessToken('Twitter');

            //  If this was a callback request from twitter, get the token
            $this->client->requestAccessToken(
                $_GET['oauth_token'],
                $_GET['oauth_verifier'],
                $_token->getRequestTokenSecret()
            );

            //  Verify creds now that we have access token
            $_result = json_decode($this->client->request('account/verify_credentials.json'));

            echo 'result: <pre>' . print_r($_result, true) . '</pre>';

            return;
        }

        if (!empty($_GET['go']) && 'go' === $_GET['go']) {
            $_token = $this->client->requestRequestToken();

            $_url = $this->client->getAuthorizationUri(['oauth_token' => $_token->getRequestToken()]);
            header('Location: ' . $_url);

            return;
        }

        $_url = $this->currentUri->getRelativeUri() . '?go=go';
        echo '<a href="' . $_url . '">Login with Twitter!</a>';
    }
}
