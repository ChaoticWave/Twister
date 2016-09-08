<?php namespace ChaoticWave\Twister\Providers;

use ChaoticWave\BlueVelvet\Providers\BaseServiceProvider;
use ChaoticWave\Twister\Services\TwisterService;

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
                return new TwisterService($app);
            });

        //  Register our facade
        if (!class_exists('Twister', false)) {
            class_alias('ChaoticWave\Twister\Facades\Twister', 'Twister');
        }
    }
}
