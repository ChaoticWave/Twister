<?php namespace ChaoticWave\Twister\Facades;

use ChaoticWave\BlueVelvet\Facades\BaseFacade;
use ChaoticWave\Twister\Providers\TwisterServiceProvider;

/**
 * @method static array getUserTimeline($parameters = [])
 * @method static array search($query, $parameters = [])
 */
class Twister extends BaseFacade
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
