<?php namespace ChaoticWave\Twister\Providers;

use ChaoticWave\BlueVelvet\Providers\BaseServiceProvider;
use ChaoticWave\Twister\Services\TwitterService;

class TwisterServiceProvider extends BaseServiceProvider
{
    //******************************************************************************
    //* Constants
    //******************************************************************************

    /** @inheritdoc */
    const ALIAS = 'twister';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function register()
    {
        $this->singleton(static::ALIAS,
            function($app) {
                return new TwitterService($app);
            });
    }
}
