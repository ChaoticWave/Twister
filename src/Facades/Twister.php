<?php namespace ChaoticWave\Twister\Facades;

use ChaoticWave\BlueVelvet\Facades\BaseFacade;
use ChaoticWave\Twister\Providers\TwisterServiceProvider;

/**
 * @method static string getRequestToken($callback = null)
 * @method static array|boolean getAccessToken($verifier = null)
 * @method static string getAuthorizeUrl($token, $login = false, $force = false)
 */
class Twitter extends BaseFacade
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    protected static function getFacadeAccessor()
    {
        return TwisterServiceProvider::ALIAS;
    }
}
