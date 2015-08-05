<?php

namespace App\Http\Controllers;

use App\Beer;
use App\BeerImg;
use App\BeerRating;


use App\Checkin;
use App\Http\Requests;
use App\Places;
use App\UserBeer;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Helpers;
use Intervention\Image\ImageManagerStatic;
use Stevebauman\Location\Facades\Location;

class BeerController extends Controller
{
    //
    public function index()
    {
    	$page_title = "Beerhit!";
		$page_descs = "what hit you?";

    	return view('beer.index',compact('page_title','page_descs'));
    }


    /*
     * Main landing page for beer
     * */
    public function beer($slug)
    {
        $page_title = "Beerhit!";
        $page_descs = "what hit you?";

        $beer = Beer::where('beers.slug',$slug)
                        ->join('beer_style','beers.style_id','=','beer_style.id')
                        ->join('brewery', 'beers.brewery_id', '=', 'brewery.id')
                        ->select('beers.*','beer_style.id as beer_style_id','beer_style.id as beer_style_style,',
                                 'brewery.slug as b_slug','brewery.id as b_id','brewery.name as b_name','brewery.city as b_city')
                        ->first();

        $similar_style = Beer::where('beers.style_id',$beer->style_id)->take(10)->get();

        $beer_id = $beer->id;

        DB::table('beers')->where('id',$beer_id)->increment('views');

        //Get other beers from the same brewery
        $from_brewery   =   Beer::where('beers.brewery_id',$beer->brewery_id)->take(10)->get();
        $beer_drink     =   UserBeer::userBeerCnt($beer_id);    //ppl who drink this
        $beer_checkin   =   Checkin::checkinBeer($beer_id);     //checkin locations with this beer
        $beerImg        =   BeerImg::getBeerImg($beer_id);      //get all uploaded beer images

        return view('beer.beer',compact('page_title','page_descs','beer','beerImg','beer_drink',
                                        'beer_ratings',
                                        'similar_style',
                                        'from_brewery',
                                        'beer_checkin'));
    }


    /*
     *  Check-in callback
     * */
    public static function checkin_callback()
    {
        if(isset(\Auth::user()->id)) {

            $checkin = new Checkin();
            $checkin->user_id   =   \Auth::user()->id;
            $checkin->place_id  =   Input::get('fb_place_id');
            $checkin->beer_id   =   Input::get('beer_id');
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
                'country'     => Input::get('fb_country'),
                'latitude'    => Input::get('fb_lat'),
                'longitude'   => Input::get('fb_lng'),
            ]);
            $place_meta = Places::updateOrCreate(['place_id'=> Input::get('fb_place_id')])->update($place);
        }
        else
        {
            return "err - user not login";
        }
    }


    public function ajaxCheckin()
    {
        if(isset(\Auth::user()->id))
        {
            $location = Location::get();
            $city = $location->cityName;
            $lat = $location->latitude;
            $lng = $location->longitude;
            $distance = 2000; //meters
            $limit=8;

            //QUERY
            $term = Input::get('term');
            $fb = \FacebookHelper::fb_init();
            $response       = $fb->get("/search?q=$term&type=place&center=$lat,$lng&distance=$distance&limit=$limit",
                                        \Auth::user()->access_token);

            $fb_response    = $response->getDecodedBody();
            $response_array = \FacebookHelper::fbResponse($fb_response['data']);

            return $response_array;
        }
        else
        {
            return "err: need login";
        }
    }


    public function add()
    {
        if(isset(\Auth::user()->id)) {
            $page_title = "Beerhit!";
            $page_descs = "what hit you?";

//            $beer = Beer::whereSlug($slug)->first();
            return view('beer.beer_add', compact('page_title', 'page_descs'));
        }
        else
        {
            return redirect("auth/login");
        }
    }


    /*
     * Edit information about beer - admin only
     * */
    public function edit($slug)
    {
        if(isset(\Auth::user()->id)) {
            $page_title = "Beerhit!";
            $page_descs = "what hit you?";

            $beer = Beer::whereSlug($slug)->first();
            return view('beer.beer_edit', compact('page_title', 'page_descs', 'beer'));
        }
        else
        {
            return redirect("auth/login");
        }
    }

    public function edit_callback($slug)
    {
        $beer_id    = Input::get('beer_id');
        $beer_name  = Input::get('name');

        if(Input::file('image'))
        {
            $imageName = "beerhit.com_".strtolower($beer_name).$beer_id.'.' . Input::file('image')->getClientOriginalExtension();

            $path = public_path('/images/logo/' . $imageName);
            $img = ImageManagerStatic::make(Input::file('image'));
            // resize the image to a width of 960 and constrain aspect ratio (auto width)
            $img->resize(100, 100 , function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($path);

            DB::table('beers')
                ->where('id',$beer_id )
                ->update(array('logo' => '/images/logo/'.$imageName));

            return redirect("beer/$slug");
        }

    }



    public function upload($slug)
    {

        $page_title = "Beerhit!";
        $page_descs = "what hit you?";
        $beer       =  Beer::whereSlug($slug)->first();

        return view('beer.beer_upload',compact('page_title','page_descs','beer'));
    }


    public function upload_callback()
    {
        if(isset(\Auth::user()->id)) {
            if (Input::file('image')) {

                $image_unique_id = date('Ymdhis') . rand(0, 9999);
                $beer_id = Input::get('beer_id');

                $beer_meta = new BeerImg();

                $beer_meta->beer_id = $beer_id;
                $beer_meta->description = Input::get('beer_description');
                $beer_name = Input::get('name');

                $imageName = "beerhit.com_" . strtolower($beer_name) . $beer_meta->beer_id . $image_unique_id . '.' . Input::file('image')->getClientOriginalExtension();


                $path = public_path('/images/catalog/' . $imageName);
                $img = ImageManagerStatic::make(Input::file('image'));
                // resize the image to a width of 960 and constrain aspect ratio (auto width)
                $img->resize(960, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($path);

                //Save image information to database
                $beer_meta->img_id = $image_unique_id;
                $beer_meta->filename = $imageName;
                $beer_meta->path = '/images/catalog/' . $imageName;
                $beer_meta->user_id = \Auth::user()->id;
                $beer_meta->save();


                /*UserActivity*/
                \UserBeerHelper::userLog(\Auth::user()->id, $beer_id, 200,0,$image_unique_id);


                $page_title = "Uploaded :  Beerhit!";
                $page_descs = "beerhit.com";
                $message = "Thanks you!";

                //Once upload is done, redirect to the image landing page
                return redirect("beer/$beer_name/gallery/picture/$image_unique_id")->with(compact('page_title', 'page_descs', 'message', 'beer_meta'));
            } else {
                return "no file entered";
            }
        }
        else
        {
            return "need to login";
        }
    }

    //
    public function gallery($slug)
    {
        $page_title = "berhit.com";
        $page_descs = "berhit.com";

        $beer = Beer::whereSlug($slug)->first();

        //ppl who drink this
        $beerImg  =  DB::table('beer_img')
            ->where('beer_id',$beer->id)
            ->select('*')
            ->get();


        return view('beer.beer_gallery',compact('page_title','page_descs','beerImg','beer'));
    }


    /*Get terms and routes to landing page
     *
     **/
    public function search()
    {
        $query = $term = Input::get('global_query');
        $page_title = "Search- $query :  Beerhit!";
        $page_descs = "beerhit.com - search: $query";

        $beers      = DB::table('beers')->where('beer', 'LIKE','%'.strtolower($query).'%')->take(15)->get();
        $brewery    = DB::table('brewery')->where('name', 'LIKE','%'.$query.'%')->take(15)->get();

        return view('pages.search',compact('page_title','page_descs','beers','brewery'));
    }


    public function submitReview()
    {

        if(isset(\Auth::user()->id)) {
            $beer_meta = new  BeerRating();

            $beer_meta->user_id = \Auth::user()->id;
            $beer_meta->comment = Input::get('rating_comments');
            $beer_meta->beer_id = Input::get('beer');
            $beer_meta->taste = Input::get('taste');
            $beer_meta->look = Input::get('look');
            $beer_meta->smell = Input::get('smell');
            $beer_meta->feel = Input::get('feel');

            //Calculate overall scores
            $overall = (Input::get('taste') + Input::get('look') + Input::get('smell') + Input::get('feel')) / 4;
            $beer_meta->overall = round($overall, 1);

            $beer_meta->save();

            //Increment votes tally
            DB::table('beers')->where('id', Input::get('beer'))->increment('votes');
            DB::table('beers')
                ->where('id', Input::get('beer'))
                ->update(array('scores' => $overall));

            return \UserBeerHelper::beerRatingContainer(Input::get('beer'));
        }
        else
        {
            return 'user need login';
        }
    }

    //AJAX Search Beer
    public function ajax_search()
    {
        $term = Input::get('term');
        if(isset($term)):
            $beers = DB::table('beers')->where('beer', 'LIKE','%'.$term.'%')->take(10)->get();

            $x=0;

            foreach($beers as $b){
                $beer_array[$x] = array("id" => $b->id,"value" => $b->beer);
                $x++;
            }
            if(isset($beer_array)){
                $beer_array = $beer_array;
            }
            else{
                $beer_array = null;
            }
            return Response::json($beer_array);
        else:
            return null;
        endif;
    }

    /*
     * Commend - drink, what
     * */
    public function commendBeer()
    {
        if(isset(\Auth::user()->id)) {

            $beer_id = Input::get('beer');
            $commend = Input::get('commend');

            switch ($commend) {
                case 1: //drink it
                    //Increment drink in beers table
                    DB::table('beers')->where('id', $beer_id)->increment('drink');
                    //Log activity
                    \UserBeerHelper::userLog(\Auth::user()->id,$beer_id,101);

                    $drink_stat = DB::table('beers')->where('id',$beer_id)->select('drink')->first();
                    return $drink_stat->drink;

                    break;

                case 2: //like it
                    //Increment drink in like table
                    DB::table('beers')->where('id', $beer_id)->increment('like');

                    //Log activity
                    \UserBeerHelper::userLog(\Auth::user()->id,$beer_id,102);

                    $drink_stat = DB::table('beers')->where('id',$beer_id)->select('like')->first();
                    return $drink_stat->like;

                    break;

                case 3://want it
                    //Increment drink in want table
                    DB::table('beers')->where('id', $beer_id)->increment('want');
                    //Log activity
                    \UserBeerHelper::userLog(\Auth::user()->id,$beer_id,103);

                    $drink_stat = DB::table('beers')->where('id',$beer_id)->select('want')->first();
                    return $drink_stat->want;

                    break;
            }
//            return $beer_id."-".$commend;
        }
        else{
            return "need <a href='/login/fb'>log in</a>";
        }
    }
}
