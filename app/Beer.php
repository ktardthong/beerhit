<?php
/**
 * Created by PhpStorm.
 * User: Kantatorn
 * Date: 7/15/2015
 * Time: 12:11 AM
 */

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Beer extends Eloquent {

    /*
     *  Get the list of beer(s) from the given brewery
     * */
    public static function breweryBeer($brewery_id)
    {
        $data = Beer::where('beers.brewery_id',$brewery_id)->take(10)->get();
        return $data;
    }

    /*
     * Check if there is a log for this beer, if not then display default icon
     * return logo
     * */
    public static function beerLogo($logo)
    {
        if(!empty($logo)):
           return  "<img src=\"$logo\" width=\"100px\">";
        else:
            return "<img src=\"/img/icons/icon_beer02.png\" class=\"img-circle\" width=\"25px\">";
        endif;
    }
} 