<?php namespace ChaoticWave\Twister\Services;

use ChaoticWave\BlueVelvet\Providers\BaseServiceProvider;
use ChaoticWave\Twister\TwitterService;

class TwitterServiceProvider extends BaseServiceProvider
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
