<?php namespace ChaoticWave\Twister\Containers;

class Contributor extends TwisterContainer
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $screen_name;
    /**
     * @var float[]
     */
    protected $coordinates;
    /**
     * @var string
     */
    protected $type;
}
