<?php
/**
 * Created by PhpStorm.
 * User: Kantatorn
 * Date: 7/28/2015
 * Time: 9:54 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Checkin extends  Eloquent{
    protected $table = 'checkin';

    /*
     * Get beers from this checkin locatino
     * */
    public static function checkinBeer($beer_id)
    {
        $data = DB::table('checkin')
                    ->groupby('checkin.place_id')
                    ->where('checkin.beer_id', $beer_id)
                    ->join('places', 'checkin.place_id', '=', 'places.place_id')->get();
        return $data;
    }
}