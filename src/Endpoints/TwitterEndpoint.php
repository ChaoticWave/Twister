<?php namespace ChaoticWave\Twister\Endpoints;

use ChaoticWave\BlueVelvet\Enums\Verbs;
use ChaoticWave\BlueVelvet\Traits\HasAppLogger;
use ChaoticWave\BlueVelvet\Utility\Uri;
use ChaoticWave\Twister\Contracts\ResourcePathAware;

/**
 * A base class for Twitter endpoints
 */
class TwitterEndpoint implements ResourcePathAware
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use HasAppLogger;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string The path base
     */
    protected $pathBase;
    /**
     * @var string The base path of this endpoint
     */
    protected $path;
    /**
     * @var array Query string parameters
     */
    protected $query;
    /**
     * @var string The verb for the endpoint
     */
    protected $method = Verbs::GET;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param string $path   The resource path
     * @param array  $query  Any query parameters
     * @param string $method The HTTP method
     */
    public function __constructor($path, $query = [], $method = Verbs::GET)
    {
        $this->setResource($path, $query, $method);
    }

    /**
     * @param string $resource The resource path
     * @param array  $query    Any query parameters
     * @param string $method   The HTTP method
     *
     * @return $this
     */
    protected function setResource($resource = null, $query = [], $method = Verbs::GET)
    {
        $this->path = $resource;
        $this->query = $query;
        $this->method = $method;

        return $this;
    }

    /** @inheritdoc */
    public function getPath($leading = false)
    {
        $_path = Uri::segment([$this->pathBase, $this->path], $leading);

        //  force json
        if ('.json' != substr($_path, -4)) {
            $_path .= '.json';
        }

        return Uri::addUrlParameter($_path, $this->query);
    }

    /** @inheritdoc */
    public function __toString()
    {
        return $this->getPath();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPathBase()
    {
        return $this->pathBase;
    }
}
