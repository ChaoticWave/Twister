<?php namespace ChaoticWave\Twister\Facades;
use ChaoticWave\BlueVelvet\Facades\BaseFacade;

/**
 * @method static void reconfig($config)
 * @method static array logs()
 * @method static string getRequestToken($oauth_callback = null)
 * @method static array|boolean getAccessToken($oauth_verifier = null)
 * @method static string getAuthorizeURL($token, $sign_in_with_twitter = true, $force_login = false)
 * @method static array query($name, $requestMethod = 'GET', $parameters = [], $multipart = false)
 * @method static array|mixed get($name, $parameters = [], $multipart = false)
 * @method static array|mixed post($name, $parameters = [], $multipart = false)
 * @method static string linkify($tweet)
 * @method static string ago($timestamp)
 * @method static string linkUser($user)
 * @method static string linkTweet($tweet)
 * @method static string linkRetweet($tweet)
 * @method static string linkReply($tweet)
 * @method static array|mixed getUserTimeline($parameters = [])
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
