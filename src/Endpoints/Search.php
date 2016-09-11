<?php namespace ChaoticWave\Twister\Traits;

use ChaoticWave\BlueVelvet\Utility\Uri;
use ChaoticWave\Twister\Endpoints\TwitterEndpoint;
use Illuminate\Http\Request;

/**
 * Search endpoints
 *
 * @method array twitterApiRequest($path, $method = Request::METHOD_GET, $payload = [], $headers = [])
 * @method string addUserParameter($path)
 */
class Search extends TwitterEndpoint
{
    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param array $query Any extra query parameters
     *
     * @return $this
     */
    public function homeTimeline($query = [])
    {
        return $this->timeline('home',$query);
    }
}
