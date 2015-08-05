<?php

use App\UserBeer;
use Illuminate\Support\Facades\Request;

/**
 * User this class for activities between user and beer
 */
class GlobalUrl
{

    /*
     *  Return User profile pic with landing url to user page
     * */
    public static function user_profile_pic($username,$path,$class="null",$width="50px")
    {
        return "<a href=\"/profile/$username\"> <img src=\"$path\" width=\"$width\" class=\"$class\"> $username</a>";
    }

    /*
     * PlaceURL
     * @todo: Use the object to create the string in the URL below
     * */
    public static function place_url($r)
    {
        $place_name = str_replace(' ','-',strtolower($r->name));
        $city_name  = str_replace(' ','-',strtolower($r->city));
        return "<a href=\"/place/$place_name-$city_name-".$r->place_id."\">".$r->name."</a>";
    }

    public static  function user_url($username)
    {
        return "<a href=\"/profile/".$username."\">$username</a>";
    }

    public static function beer_url($slug,$beer)
    {
        return "<a href=\"/beer/$slug\">$beer</a>";
    }

    ///beer/Gambrinus/gallery/picture/201507200248331809
    public static function beer_image($name,$img_id,$content)
    {
        $name=strtolower($name);
        return "<a href=\"/beer/$name/gallery/picture/$img_id\">$content</a>";
    }
}
