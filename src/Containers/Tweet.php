<?php namespace ChaoticWave\Twister\Containers;

class Tweet extends TwisterContainer
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var string The tweet id
     */
    protected $id;
    /**
     * @var array
     */
    protected $annotations;
    /**
     * @var Contributor[]
     */
    protected $contributors;
    /**
     * @var float[]
     */
    protected $coordinates;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var array
     */
    protected $current_user_retweet;
    /**
     * @var int[]
     */
    protected $display_text_range;
    /**
     * @var array
     */
    protected $entities;
    /**
     * @var ExtendedTweet
     */
    protected $extended_tweet;
    /**
     * @var int
     */
    protected $favorite_count;
    /**
     * @var bool
     */
    protected $favorited = false;
    /**
     * @var string
     */
    protected $filter_level;
    /**
     * @var array
     */
    protected $geo;
    /**
     * @var string
     */
    protected $in_reply_to_screen_name;
    /**
     * @var string
     */
    protected $in_reply_to_status_id;
    /**
     * @var string
     */
    protected $in_reply_to_user_id;
    /**
     * @var string
     */
    protected $lang;
    /**
     * @var Place
     */
    protected $place;
    /**
     * @var bool
     */
    protected $possibly_sensitive = false;
    /**
     * @var string
     */
    protected $quoted_status_id;
    /**
     * @var Tweet
     */
    protected $quoted_status;
    /**
     * @var array
     */
    protected $scopes;
    /**
     * @var int
     */
    protected $retweet_count;
    /**
     * @var bool
     */
    protected $retweeted = false;
    /**
     * @var Tweet
     */
    protected $retweeted_status;
    /**
     * @var string
     */
    protected $source;
    /**
     * @var string
     */
    protected $text;
    /**
     * @var bool
     */
    protected $truncated = false;
    /**
     * @var array
     */
    protected $user;
    /**
     * @var bool
     */
    protected $withheld_copyright = false;
    /**
     * @var string[]
     */
    protected $withheld_in_countries;
    /**
     * @var string
     */
    protected $withheld_scope;
    /** @inheritdoc */
    protected $_loadable = [
        'contributors'     => ['class' => Contributor::class, 'array' => true],
        'place'            => ['class' => Place::class, 'array' => false],
        'quoted_status'    => ['class' => Tweet::class, 'array' => false],
        'retweeted_status' => ['class' => Tweet::class, 'array' => false],
        'extended_tweet'   => ['class' => ExtendedTweet::class, 'array' => false],
    ];
}
