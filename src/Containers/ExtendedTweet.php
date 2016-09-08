<?php namespace ChaoticWave\Twister\Containers;

class ExtendedTweet extends TwisterContainer
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string
     */
    protected $full_text;
    /**
     * @var int[]
     */
    protected $display_text_range;
    /**
     * @var array
     */
    protected $entities;
    /**
     * @var array
     */
    protected $extended_entities;
}
