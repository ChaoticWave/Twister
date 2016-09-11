<?php namespace ChaoticWave\Twister\Traits;

use ChaoticWave\BlueVelvet\Enums\Verbs;
use ChaoticWave\Twister\Endpoints\TwitterEndpoint;

/**
 * Status endpoints
 */
class Status extends TwitterEndpoint
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $pathBase = 'statuses';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param array $query Any extra query parameters
     *
     * @return string
     */
    public function userTimeline($query = [])
    {
        return $this->setResource('user_timeline', $query);
    }

    /**
     * @param array $query Any extra query parameters
     *
     * @return $this
     */
    public function homeTimeline($query = [])
    {
        return $this->setResource('home_timeline', $query);
    }

    /**
     * @param array $query Any extra query parameters
     *
     * @return $this
     */
    public function mentionsTimeline($query = [])
    {
        return $this->setResource('mentions_timeline', $query);
    }

    /**
     * @param array $query Any extra query parameters
     *
     * @return $this
     */
    public function retweetsOfMe($query = [])
    {
        return $this->setResource('retweets_of_me', $query);
    }

    /**
     * @param string $id    The original tweet id
     * @param array  $query Any extra query parameters
     *
     * @return $this
     */
    public function retweets($id, $query = [])
    {
        return $this->setResource('retweets/' . $id, $query);
    }

    /**
     * @param string $id    The original tweet id
     * @param array  $query Any extra query parameters
     *
     * @return $this
     */
    public function show($id, $query = [])
    {
        return $this->setResource('show/' . $id, $query);
    }

    /**
     * @param string $id    The original tweet id
     * @param array  $query Any extra query parameters
     *
     * @return $this
     */
    public function destroy($id, $query = [])
    {
        return $this->setResource('destroy/' . $id, $query);
    }

    /**
     * @param array $query Any extra query parameters
     *
     * @return $this
     */
    public function update($query = [])
    {
        return $this->setResource('retweets/update', $query);
    }

    /**
     * @param string $id    The original tweet id
     * @param array  $query Any extra query parameters
     *
     * @return $this
     */
    public function retweet($id, $query = [])
    {
        return $this->setResource('retweet/' . $id, $query);
    }

    /**
     * @param string $id    The original tweet id
     * @param array  $query Any extra query parameters
     *
     * @return $this
     */
    public function unretweet($id, $query = [])
    {
        return $this->setResource('unretweet/' . $id, $query);
    }
}
