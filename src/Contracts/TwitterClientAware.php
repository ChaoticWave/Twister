<?php namespace ChaoticWave\BlueVelvet\Contracts;

interface TwitterClientAware
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @return \OAuth\OAuth1\Service\Twitter
     */
    function getClient();
}
