<?php namespace ChaoticWave\Twister\Services;

use ChaoticWave\BlueVelvet\Services\BaseService;
use ChaoticWave\Twister\Endpoints\TwitterEndpoint;
use ChaoticWave\Twister\Traits\OAuthService;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth1\Service\Twitter;

class TwisterService extends BaseService
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @var string The client's service name
     */
    const SERVICE_NAME = 'Twitter';

    //******************************************************************************
    //* Traits
    //******************************************************************************

    use OAuthService;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var TwitterEndpoint[]
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

        $this->setupOAuthService(static::SERVICE_NAME, config('twister.store'), config('twister.default_ttl'));
        $this->setUser(config('twister.user'));

        $this->initEndpoints();
    }

    protected function initEndpoints($path = null)
    {
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

        return json_decode($this->service->request('statuses/user_timeline.json?' . implode('&', $_query)));
    }

}
