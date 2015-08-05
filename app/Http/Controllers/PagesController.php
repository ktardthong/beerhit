<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use App\UserBeer;
use Illuminate\Support\Facades\DB;
use App\Places;
use App\BeerImg;
use Illuminate\Support\Facades\Helpers;

class PagesController extends Controller
{
	public function index()
	{
		$page_title     =   "Beerhit!";
		$page_descs     =   "what hit you?";

        $beer_img       =   BeerImg::recentImg();
        $recent_drink   =   UserBeer::recentDrink();
        $recent_checkin =   Places::recentCheckIn();

        if(!empty(\Auth::user()->fb_id)):
            $user_friends   =   User::userFriend(\Auth::user()->fb_id);
        endif;

		return view('pages.home',compact('page_title','page_descs','beer_img','recent_drink','recent_checkin','user_friends'));
	}

    public function about()
    {
    	return view('pages.about');
    }

}
