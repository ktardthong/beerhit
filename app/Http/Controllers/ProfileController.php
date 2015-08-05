<?php

namespace App\Http\Controllers;

use App\UserBeer;
use App\Http\Requests;
use App\User;
use App\BeerImg;
use \App\Checkin;
use \App\Places;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use Stevebauman\Location\Facades\Location;

class ProfileController extends Controller
{

    /**
     * Insert whatever user is drinking (hope it's good))
     */
    public function drinking_this()
    {
        if(Auth::user()):
            //Activity log
            \UserBeerHelper::userLog(\Auth::user()->id,Input::get('beer'),101);
        else:
            echo "not allow";
        endif;
    }


    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $page_title = "Beerhit!";
        $page_descs = "what hit you?";

        $location = Location::get();

        //data to display on profile page
        $user           =   User::where('username', $slug)->first();
        if (!$user) {
            return redirect(404);
        }
        else
        {
            $user_checkin   =   User::getUserCheckIn($user->id);
            $uploaded       =   BeerImg::userUploaded($user->id);
            $drink_log      =   UserBeer::userDrinkLog($user->id);
            $userLike       =   UserBeer::userBeerLike($user->id);
            $drink_cnt      =   UserBeer::where('user_id',$user->id)->count();

            $user_flg = isset(Auth::user()->id)?TRUE:FALSE;


            $data = array('page_title'      => $page_title,
                          'page_descs'      => $page_descs,
                          'user'            => $user,
                          'drink_cnt'       => $drink_cnt,
                          'drink_log'       => $drink_log,
                          'drink_uploaded'  => $uploaded,
                          'user_flg'        => $user_flg,
                          'user_like'       => $userLike,
                          'user_checkin'    => $user_checkin,
                          'location'        => $location,
                         );
            return view('pages.user',$data);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        if(isset(Auth::user()->id))
        {
            $page_title = "Beerhit!";
            $page_descs = "what hit you?";

            $user = User::where('username', $slug)->first();
            $data = array('page_title' => $page_title,
                'page_descs' => $page_descs,
                'user' => $user,
            );
            return view('edit.edit_user', $data);
        }
        else
        {
            return redirect('/auth/login');
        }
    }

    /*
     * Callback for EDIT function
     * */
    public function edit_callback()
    {
            $user_meta      =   User::find(Auth::user()->id);
            $username_flg   =   $user_meta->username_flg;
            $img_path       =   $user_meta->avatar;

            /*if there is image*/
            if(Input::file('image'))
            {
                $imageName = "beerhit.com_".Auth::user()->fb_id.'.' . Input::file('image')->getClientOriginalExtension();
                $img_path  = '/images/profiles/'.$imageName;
                $path = public_path('/images/profiles/' . $imageName);
                $img = ImageManagerStatic::make(Input::file('image'));
                // resize the image to a width of 960 and constrain aspect ratio (auto width)
                $img->resize(150, NULL , function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($path);
            }

            if($username_flg ==0):
                $username       =   Str::slug(Input::get('username'));
                $username_flg   =   1;
            else:
                $username       =   $user_meta->username;
            endif;

            $user_meta->firstname   =   Input::get('firstname');
            $user_meta->lastname    =   Input::get('lastname');
            $user_meta->email       =   Input::get('email');
            $user_meta->city        =   Input::get('city');
            $user_meta->country     =   Input::get('country');
            $user_meta->username    =   $username;
            $user_meta->username_flg=   $username_flg;
            $user_meta->avatar      =   $img_path;
            $user_meta->save();
            //User::updateOrCreate(['id'=> Auth::user()->id])->update($user_meta);

            return redirect("/profile/$username");
    }


    /*
     * Register new user
     * */
    public function register_callback()
    {
        /*if there is image*/
        if(Input::file('image'))
        {
            $image_unique_id = date('Ymdhis') . rand(0, 9999);
            $imageName = "beerhit.com_".$image_unique_id.'.' . Input::file('image')->getClientOriginalExtension();
            $img_path  = '/images/profiles/'.$imageName;
            $path = public_path('/images/profiles/' . $imageName);
            $img = ImageManagerStatic::make(Input::file('image'));
            // resize the image to a width of 960 and constrain aspect ratio (auto width)
            $img->resize(80, null , function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($path);
        }
        $user_meta = new User();
        $user_meta->fb_id        =   Input::get('fb_id');
        $user_meta->access_token=   Input::get('access_token');
        $user_meta->firstname   =   Input::get('firstname');
        $user_meta->lastname    =   Input::get('lastname');
        $user_meta->email       =   Input::get('email');
        $user_meta->city        =   Input::get('city');
        $user_meta->country     =   Input::get('country');
        $user_meta->username    =   Str::slug(Input::get('username'));
        $user_meta->username_flg=   1;
        $user_meta->avatar      =   !empty($img_path)?$img_path:Input::get('avatar');
        $user_meta->gender      =   Input::get('gender');
        $user_meta->provider    =   'facebook';
        $user_meta->save();
        //User::updateOrCreate(['id'=> Auth::user()->id])->update($user_meta);
        $username = $user_meta->username;

        $user = User::where('fb_id', $user_meta->fb_id)->first();
        Auth::loginUsingId($user->id);

        return redirect("/profile/$username");
    }

    /*
     * Callback when user post
     * */
    public function userPost_callback()
    {
        if(isset(\Auth::user()->id))
        {
            //All the vars
            $user_id        = \Auth::user()->id;
            $beer_id        = Input::get('beer_id');
            $fb_place_id    = Input::get('fb_place_id');
            $image_unique_id = 0;

            if (Input::file('image'))
            {
                BeerImg::uploadBeerImg_callback(Input::get(),Input::file('image'));
            }

            if(!empty($ratingComment))
            {
                //Preparing data to be added
                $beer_meta  =  \App\BeerRating::Add(Input::get());
                $overall    =   \App\BeerRating::overallCalc(Input::get());

                //Calculate overall scores
                $beer_meta->overall = round($overall, 1);
                $beer_meta->save();

                //Increment votes tally
                DB::table('beers')->where('id', Input::get('beer_id'))->increment('votes');
                DB::table('beers')->where('id', Input::get('beer_id'))->update(array('scores' => $overall));
            }

            if(!empty($fb_place_id)) //if there is place ID
            {
                $checkin = new Checkin();
                $checkin->user_id = $user_id;
                $checkin->place_id = $fb_place_id;
                $checkin->beer_id = $beer_id;
                $checkin->source = 1;  //we are using facebook, 1 for now
                $checkin->save();

                $place = ([
                    'place_id' => Input::get('fb_place_id'),
                    'name' => Input::get('location'),
                    'category' => Input::get('fb_category'),
                    'street' => Input::get('fb_address'),
                    'city' => Input::get('fb_city'),
                    'state' => Input::get('fb_state'),
                    'zip' => Input::get('fb_zip'),
                    'latitude' => Input::get('fb_lat'),
                    'longitude' => Input::get('fb_lng'),
                ]);

                $place_meta = Places::updateOrCreate(['place_id' => Input::get('fb_place_id')])->update($place);

                //Log information
                \UserBeerHelper::userLog($user_id,$beer_id,300,$fb_place_id,$image_unique_id);
            }


            return redirect("profile/".\Auth::user()->username);

        }
        else
        {
            return "err - user not login";
        }
    }

    /*
     * Log user out
     * */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


    public function userRating()
    {
        return \UserBeerHelper::beerRatingUser(Input::get('beer'));
    }
}
