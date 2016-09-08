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
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\ServiceFactory;

class TwisterService extends BaseService
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @var string The client's service id
     */
    const SERVICE_ID = 'twitter';
    /**
     * @var string The client's service name
     */
    const SERVICE_NAME = 'Twitter';

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
    /**
     * @var string The user who's feed to pull
     */
    protected $user;

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
        $this->user = config('twister.user');

        $this->credentials = new Credentials(
            config('twister.secrets.consumer_key'),
            config('twister.secrets.consumer_secret'),
            $this->currentUri->getAbsoluteUri()
        );

        $this->client = $this->services->createService(static::SERVICE_ID, $this->credentials, $this->store);

        if (null !== ($_accessToken = config('twister.secrets.access_token'))) {
            if (null !== ($_accessTokenSecret = config('twister.secrets.access_token_secret'))) {
                $_token = new StdOAuth1Token($_accessToken);
                $_token->setAccessTokenSecret($_accessTokenSecret);
                $this->store->storeAccessToken(static::SERVICE_NAME, $_token);
            }
        }

        $this->checkForInteractiveRequest();
    }

    /**
     * @param int $count
     *
     * @return array
     */
    public function getUserTimeline($count = 25)
    {
        $_query = ['count=' . $count];

        if ($this->user) {
            $_query[] = 'screen_name=' . $this->user;
        }

        return json_decode($this->client->request('statuses/user_timeline.json?' . implode('&', $_query)));
    }

    /**
     * @param bool $echo
     *
     * @return mixed|null
     */
    protected function checkForInteractiveRequest($echo = false)
    {
        if (!empty($_GET['oauth_token'])) {
            $_token = $this->store->retrieveAccessToken(static::SERVICE_NAME);

            //  If this was a callback request from twitter, get the token
            $this->client->requestAccessToken(
                $_GET['oauth_token'],
                $_GET['oauth_verifier'],
                $_token->getRequestTokenSecret()
            );

            //  Verify creds now that we have access token
            return json_decode($this->client->request('account/verify_credentials.json'));
        }

        if (!empty($_GET['go']) && 'go' === $_GET['go']) {
            $_token = $this->client->requestRequestToken();

            $_url = $this->client->getAuthorizationUri(['oauth_token' => $_token->getRequestToken()]);
            header('Location: ' . $_url);

            return true;
        }

        if ($echo) {
            $_url = $this->currentUri->getRelativeUri() . '?go=go';
            echo '<a href="' . $_url . '">Login with Twitter!</a>';

            return true;
        }

        return false;
    }
}
