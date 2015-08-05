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
use Illuminate\Support\Facades\Request;

class Places extends  Eloquent{
    protected $table = 'places';

    protected $fillable =   array('place_id','name', 'category','street','city','country','state','zip','latitude','longitude','created_at','updated_at');

    /*
     * Get all the recent checkin
     * @return distinct place
     * */
    public static function recentCheckIn()
    {
        $data   =   DB::table('checkin')
                        ->groupBy('checkin.place_id')
                        ->join('places','checkin.place_id','=','places.place_id')
                        ->get();
        return $data;
    }


    /*
     * Get user drink what beer at this placce
     * @return first() for user drink what beer
     * */
    public  static function userBeerPlace($user_id, $beer_id, $place_id)
    {
        $data   =   DB::table('checkin')
                                ->where('checkin.place_id',$place_id)
                                ->where('checkin.user_id',$user_id)
                                ->where('checkin.beer_id',$beer_id)
                                ->join('places','checkin.place_id','=','places.place_id')
                                ->join('beers','beers.id','=','checkin.beer_id')
                                ->select('places.name','places.place_id','places.city',
                                         'beers.beer')
                                ->first();
        return $data;
    }

    /*
     * Get list of user who check in at this place
     * */
    public static function userCheckin($place_id)
    {
        $data   =   DB::table('checkin')
                        ->groupBy('checkin.user_id')
                        ->where('checkin.place_id',$place_id)
                        ->join('users','checkin.user_id','=','users.id')
                        ->get();
        return $data;
    }


    /*
     * Get images from check-in locatino
     * */
    public static function imagesCheckin($place_id)
    {
        $data = DB::table('beer_img')->where('place_id',$place_id)->get();
        return $data;
    }
}