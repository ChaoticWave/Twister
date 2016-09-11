<?php namespace ChaoticWave\Twister\Endpoints;

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

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param string $path  The resource path
     * @param array  $query Any query parameters
     */
    public function __constructor($path, $query = [])
    {
        $this->setResource($path, $query);
    }

    /**
     * @param string $resource
     * @param array  $query   Any query parameters
     * @param bool   $leading If you want a leading slash or not
     *
     * @return $this
     */
    protected function setResource($resource = null, $query = [], $leading = false)
    {
        $this->path = $resource;
        $this->query = $query;

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

    /**
     * @return string
     */
    public function getPathBase()
    {
        return $this->pathBase;
    }

    /** @inheritdoc */
    public function __toString()
    {
        return $this->getPath();
    }
}
