<?php namespace ChaoticWave\Twister\Contracts;

/**
 * Something that knows of a resource path
 */
interface ResourcePathAware
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param bool $leading If true, a leading slash will be added
     *
     * @return string The resource path
     */
    public function getPath($leading = false);

    /**
     * @return string The resource path
     */
    public function __toString();
}
