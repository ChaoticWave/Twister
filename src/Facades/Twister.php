<?php namespace ChaoticWave\Twister\Facades;

use ChaoticWave\BlueVelvet\Facades\BaseFacade;
use ChaoticWave\Twister\Providers\TwisterServiceProvider;

/**
 * @method static array getUserTimeline()
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
