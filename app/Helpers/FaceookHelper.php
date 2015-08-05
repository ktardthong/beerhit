<?php

use App\UserBeer;
use Illuminate\Support\Facades\Request;
use Facebook\Facebook;

/**
 * User this class for activities between user and beer
 */
class FacebookHelper
{
    public static function fb_init()
    {
        $fb = new \Facebook\Facebook([
            'app_id' => '182388651773669',
            'app_secret' => '3b63ec9b8ba39d8a70c9b6bcc588dfae',
            'default_graph_version' => 'v2.4',
            //'default_access_token' => '{access-token}', // optional
        ]);
        return $fb;
    }


    /*
     * Convert facebook response to array
     * @params facebookResponse body
     * @return Array
     * */
    public static function fbResponse($data)
    {
        $x = 0;
        $response_array = null;
        foreach ($data as $response)
        {
            $response_array[$x] = array("id" => $response['id'],
                "value" => $response['name'],
                "street" => $response['location']['street'],
                "category" => $response['category'],
                "lat" => $response['location']['latitude'],
                "lng" => $response['location']['longitude'],
                "city" => $response['location']['city'],
                "fb_state" => $response['location']['state'],
                "country" => $response['location']['country'],
                "zip" => $response['location']['zip'],
            );
            $x++;
        }
        return $response_array;
    }
}

