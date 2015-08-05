<?php
namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Symfony\Component\HttpFoundation\Request;

class BeerRating  extends  Eloquent{
    protected $table = 'beer_ratings';

    public static function Add($request)
    {
        $beer_meta = new \App\BeerRating();

        $beer_meta->user_id = \Auth::user()->id;
        $beer_meta->comment = $request['ratingComment'];
        $beer_meta->beer_id = $request['beer_id'];
        $beer_meta->taste   = $request['tasteInput'];
        $beer_meta->look    = $request['lookInput'];
        $beer_meta->smell   = $request['smellInput'];
        $beer_meta->feel    = $request['feelInput'];

        return $beer_meta;
    }

    public static function overallCalc($request)
    {
        $overall = ($request['tasteInput']  +
                    $request['lookInput']   +
                    $request['smellInput']  +
                    $request['feelInput'])  / 4;
        return $overall;
    }
}