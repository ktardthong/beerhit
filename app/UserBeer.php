<?php
/**
 * Created by PhpStorm.
 * User: Kantatorn
 * Date: 7/17/2015
 * Time: 11:56 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class UserBeer  extends  Eloquent{
    protected $table = 'user_beer';

    /*
     * Get how many users drink this beer
     * @return count
     * */
    public static function userBeerCnt($beer_id)
    {
        $data = DB::table('user_beer')
                    ->where('beer_id',$beer_id)
                    ->count();
        return $data;
    }


    /*
     * Get the most recent drink from every users
     * @params NONE
     * @return data as results object
     * */
    public static function recentDrink()
    {
        $data = DB::table('user_beer')
                    ->orderBy('user_beer.created_at','desc')
                    ->where('user_beer.type_id',101)
                    ->join('beers', 'beers.id', '=', 'user_beer.beer_id')
                    ->take(10)
                    ->groupBy('user_beer.beer_id')
                    ->select('beers.beer','beers.slug','beers.scores','beers.logo',
                             'user_beer.created_at')
                    ->get();
        return $data;
    }


    public static function highestRated()
    {
        
    }

    /*
     * Get user drink log from user id
     * @param   $user_id
     * @return  $data as table results
     * */
    public static function userDrinkLog($user_id)
    {
        $data = DB::table('user_beer')
            ->orderby('user_beer.updated_at', 'desc')
            ->where('users.id', $user_id)
            ->join('beers', 'beers.id', '=', 'user_beer.beer_id')
            ->join('users', 'user_beer.user_id', '=', 'users.id')
            ->select('beers.*', 'user_beer.*')
            ->get();
        return $data;
    }


    /*
     * Get Beer that user LIKE
     * @param   $user_id
     * @return  $data as table results
     * */
    public static function userBeerLike($user_id)
    {
        $data = DB::table('user_beer')
                    ->orderby('user_beer.updated_at','desc')
                    ->where('users.id',$user_id)
                    ->where('user_beer.type_id',102)
                    ->join('beers', 'beers.id', '=', 'user_beer.beer_id')
                    ->join('users', 'user_beer.user_id', '=', 'users.id')
                    ->select('beers.*', 'user_beer.*')
                    ->get();
        return $data;
    }
}