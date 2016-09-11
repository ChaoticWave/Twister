<?php namespace ChaoticWave\Twister\OAuth\Storage;

use Illuminate\Support\Facades\Cache;
use OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\TokenInterface;

class LaravelCache implements TokenStorageInterface
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /**
     * @var int The default cache TTL, 20 minutes
     */
    const DEFAULT_CACHE_TTL = 20;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string The store name
     */
    protected $store;
    /**
     * @var string The storage key
     */
    protected $key;
    /**
     * @var int Lifetime of cached data in minutes
     */
    protected $cacheTtl;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param string   $key      The storage key
     * @param string   $store    The cache store name to use, or null for default
     * @param int|null $cacheTtl The cache time to live
     */
    public function __construct($key, $store = null, $cacheTtl = null)
    {
        $this->store = $store;
        $this->key = $key;
        $this->cacheTtl = $cacheTtl ?: static::DEFAULT_CACHE_TTL;
    }

    /**
     * @param string $service
     *
     * @return TokenInterface|null
     * @throws TokenNotFoundException
     */
    public function retrieveAccessToken($service)
    {
        if (false !== ($_token = $this->hasAccessToken($service))) {
            return $_token;
        }

        throw new TokenNotFoundException('Token not found. Please ensure it is being stored.');
    }

    /**
     * @param string         $service
     * @param TokenInterface $token
     *
     * @return TokenStorageInterface
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        $this->oauthStorePut($service, 'access_token', $token);

        return $this;
    }

    /**
     * @param string $service
     *
     * @return TokenInterface|bool
     */
    public function hasAccessToken($service)
    {
        return $this->oauthStoreGet($service, 'access_token') ?: false;
    }

    /**
     * {@inheritDoc}
     */
    public function clearToken($service)
    {
        if ($this->hasAccessToken($service)) {
            /** @noinspection PhpUndefinedMethodInspection */
            $_cached = Cache::get($this->key, []);
            array_forget($_cached, [$service]);
            /** @noinspection PhpUndefinedMethodInspection */
            Cache::put($this->key, $_cached, $this->cacheTtl);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearAllTokens()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        Cache::forget($this->key);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function storeAuthorizationState($service, $state)
    {
        $this->oauthStorePut($service, 'state', $state);

        return $this;
    }

    /** @inheritdoc */
    public function hasAuthorizationState($service)
    {
        return $this->oauthStoreGet($service, 'state', false);
    }

    /** @inheritdoc */
    public function retrieveAuthorizationState($service)
    {
        if (false !== ($_state = $this->hasAuthorizationState($service))) {
            return $_state;
        }

        throw new AuthorizationStateNotFoundException('State not found. Please ensure it is being stored.');
    }

    /** @inheritdoc */
    public function clearAuthorizationState($service)
    {
        if ($this->hasAuthorizationState($service)) {
            /** @noinspection PhpUndefinedMethodInspection */
            $_cached = Cache::get($this->key, []);
            array_forget($_cached, [$service . '.state']);
            /** @noinspection PhpUndefinedMethodInspection */
            Cache::put($this->key, $_cached, $this->cacheTtl);
        }

        return $this;
    }

    /** @inheritdoc */
    public function clearAllAuthorizationStates()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $_cached = Cache::get($this->key, []);

        //  Loop through the cache and remove states
        foreach ($_cached as $_id => $_node) {
            array_forget($_cached[$_id], ['state']);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        Cache::put($this->key, $_cached, $this->cacheTtl);

        return $this;
    }

    /**
     * @return int
     */
    public function getCacheTtl()
    {
        return $this->cacheTtl;
    }

    /**
     * @param int $cacheTtl
     *
     * @return $this
     */
    public function setCacheTtl($cacheTtl)
    {
        $this->cacheTtl = $cacheTtl ?: static::DEFAULT_CACHE_TTL;

        return $this;
    }

    /**
     * Retrieves stored OAuth data from the Laravel cache
     *
     * @param string     $service The service name
     * @param string     $key     The key to retrieve
     * @param mixed|null $default The default value
     *
     * @return mixed|null
     */
    protected function oauthStoreGet($service, $key = null, $default = null)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $_data = Cache::store($this->store)->get($this->key, []);

        return array_get($_data, $service . ($key ? '.' . $key : null), $default);
    }

    /**
     * Stores OAuth data into the Laravel cache
     *
     * @param string     $service The service name
     * @param string     $key     The storage key
     * @param mixed|null $value   The data to store
     */
    protected function oauthStorePut($service, $key = null, $value = null)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $_data = Cache::store($this->store)->get($this->key, []);
        array_set($_data, $service . ($key ? '.' . $key : null), $value);

        /** @noinspection PhpUndefinedMethodInspection */
        Cache::store($this->store)->put($this->key, $_data, $this->cacheTtl);
    }
}
