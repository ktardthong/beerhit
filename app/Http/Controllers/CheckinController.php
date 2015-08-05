<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\BeerImg;
use \App\Beer;
use \App\Checkin;
use \App\Places;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    public static function checkin(Request $request)
    {
        $page_title = "Beerhit!";
        $page_descs = "what hit you?";

        if(\Auth::user()):
            switch($request->id):
                case 'drink';
                        $beer       =  Beer::whereSlug($request->name)->first();
                    break;
                case 'place';
                        $fb_place_id = substr(strrchr($request->name, "-"), 1);
                        $place       =  Places::where('place_id',$fb_place_id)->first();
                    break;
                case 'user';
                        echo 'user';
                    break;
            endswitch;

            return view('beer.beer_checkin',compact('page_title','page_descs','beer','place'));
        else:
            return redirect("/auth/login");
        endif;
    }


    /*
     * Check-in callback
     * */
    public static function checkin_callback()
    {
        if(isset(\Auth::user()->id))
        {
            //All the vars
            $user_id        = \Auth::user()->id;
            $beer_id        = Input::get('beer_id');
            $fb_place_id    = Input::get('fb_place_id');
            $image_unique_id = 0;

            /*
             * If there is any images
             * */
            if (Input::file('image'))
            {
                $image_unique_id = date('Ymdhis') . rand(0, 9999);

                $beer_meta = new BeerImg();

                $beer_meta->beer_id = $beer_id;
                $beer_meta->description = Input::get('description');
                $beer_name = Input::get('name');

                $imageName = "beerhit.com_" . strtolower($beer_name) . $beer_meta->beer_id . $image_unique_id . '.' . Input::file('image')->getClientOriginalExtension();

                $path = public_path('/images/catalog/' . $imageName);
                $img = ImageManagerStatic::make(Input::file('image'));

                // resize the image to a width of 960
                // and constrain aspect ratio (auto width)
                $img->resize(960, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($path);

                //Save image information to database
                $beer_meta->img_id      =   $image_unique_id;
                $beer_meta->filename    =   $imageName;
                $beer_meta->path        =   '/images/catalog/' . $imageName;
                $beer_meta->user_id     =   \Auth::user()->id;
                $beer_meta->place_id    =   $fb_place_id;
                $beer_meta->save();
            }


            $checkin = new Checkin();
            $checkin->user_id   =   $user_id;
            $checkin->place_id  =   $fb_place_id;
            $checkin->beer_id   =   $beer_id;
            $checkin->source    =   1;  //we are using facebook, 1 for now
            $checkin->save();

            $place  =    ([
                'place_id'    => Input::get('fb_place_id'),
                'name'        => Input::get('location'),
                'category'    => Input::get('fb_category'),
                'street'      => Input::get('fb_address'),
                'city'        => Input::get('fb_city'),
                'state'       => Input::get('fb_state'),
                'zip'         => Input::get('fb_zip'),
                'latitude'    => Input::get('fb_lat'),
                'longitude'   => Input::get('fb_lng'),
            ]);
            $place_meta = Places::updateOrCreate(['place_id'=> Input::get('fb_place_id')])->update($place);

            //Log information
            \UserBeerHelper::userLog($user_id,$beer_id,300,$fb_place_id,$image_unique_id);

            return redirect("profile/".\Auth::user()->username);
        }
        else
        {
            return "err - user not login";
        }
    }

}