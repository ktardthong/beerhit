<?php

namespace App\Http\Controllers;

use App\Places;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlacesController extends Controller
{

    /*
     * Landing page for place
     * */
    public static function place(Request $request)
    {
        $fb_place_id = substr(strrchr($request->name, "-"), 1);

        $place_info = \App\Places::where('place_id',$fb_place_id)->first();

        $drink_here = DB::table('checkin') ->groupBy('checkin.beer_id')->where('checkin.place_id',$fb_place_id)
                            ->join('places','checkin.place_id','=','places.place_id')
                            ->join('beers','beers.id','=','checkin.beer_id')
                            ->take(5)
                            ->get();

        $been_here  = Places::userCheckin($fb_place_id);
        $img_here   = Places::imagesCheckin($fb_place_id);

        $page_title = "Beerhit!";
        $page_descs = "what hit you?";

        return view('places.place',compact('page_title','page_descs','place_info','drink_here','been_here','img_here'));
    }



}