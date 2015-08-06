<?php

use App\User;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::bind('beer',function($value){

});


//PAGES ROUTES
Route::get('/','PagesController@index');
Route::get('about','PagesController@about');

//CHECK-IN
Route::get('/checkin/{id}/{name}','CheckinController@checkin');
Route::post('/checkin_callback','CheckinController@checkin_callback');

//PLACES ROUTES
Route::get('place/{name}','PlacesController@place');

//BEER ROUTES
Route::get('beer','BeerController@index');
Route::get('beer/{name}','BeerController@beer');
Route::get('add_beer','BeerController@add');
Route::get('beer/{name}/edit','BeerController@edit');
Route::post('beer/{name}/edit_callback','BeerController@edit_callback');

Route::get('beer/{name}/upload','BeerController@upload');
Route::post('beer/{name}/upload_callback','BeerController@upload_callback');
//Route::get('beer/{name}/gallery/picture/{id}','BeerController@picture');
Route::get('beer/{name}/gallery/','BeerController@gallery');
Route::get('beer/{name}/gallery/picture/{id}', function ($name,$id) {

    $page_title = "Uploaded :  Beerhit!";
    $page_descs = "beerhit.com";
    $beer_img = DB::table('beer_img')
                                    ->where('beer_img.img_id',$id)
                                    ->join('beers', 'beers.id', '=', 'beer_img.beer_id')
                                    ->join('users', 'beer_img.user_id', '=', 'users.id')
                                    ->select('beer_img.*',
                                             'beer_img.created_at as beer_created',
                                             'beers.slug',
                                             'beers.beer',
                                             'users.id',
                                             'users.username',
                                             'users.avatar'
                                            )
                                    ->first();


    $data_page = array('page_title' => $page_title,
                  'page_descs' => $page_descs,
                  'data'       => $beer_img);

    return view('beer.beer_picture  ',$data_page);

});


//Register callback
Route::post('register','ProfileController@register');
Route::post('register_callback','ProfileController@register_callback');


//PROFILE ROUTES
Route::get('profile/{name}','ProfileController@show');
Route::get('profile/{name}/edit','ProfileController@edit');
Route::post('profile/{name}/edit_callback','ProfileController@edit_callback');
Route::get('profile/{name}/gallery','ProfileController@logout');
Route::get('profile/{name}/logout','ProfileController@logout');
Route::post('userPost_callback','ProfileController@userPost_callback');

Route::post('search','BeerController@search');
Route::get('auth/login','Auth\AuthController@login');



/*AJAX*/
Route::post('ajax/drinking_this', 'ProfileController@drinking_this');
Route::get('ajax/searchBeer', 'BeerController@ajax_search');
Route::post('ajax/commendBeer', 'BeerController@commendBeer');
Route::post('ajax/submitReview', 'BeerController@submitReview');
Route::get('ajax/ajaxCheckin', 'BeerController@ajaxCheckin');
Route::POST('ajax/userRating', 'ProfileController@userRating');
//----------------------------------------------------------------




Route::get('login/fb', function() {
    return \Socialite::with('facebook')
                        ->scopes(['email','user_friends','user_location','user_birthday'])
                        ->redirect();
});

//http://beerhit.com/login/fb/callback
Route::get('login/fb/callback', function() {
    $socialize_user =  \Socialite::with('facebook')->user();
    $facebook_user_id = $socialize_user->getId(); // unique facebook user id
    $user = User::where('email', $socialize_user->email)->first();

    $location = Location::get();
    $city       =   $location->cityName;
    $country    =   $location->countryName;

//    dd($socialize_user);
    if (!$user) {
        $user = new User;
        $user->fb_id        = $facebook_user_id;
        $user->firstname    = $socialize_user->user['first_name'];
        $user->lastname     = $socialize_user->user['last_name'];
        $user->username     = $socialize_user->name;
        $user->email        = $socialize_user->email;
        $user->avatar       = $socialize_user->avatar;
        $user->gender       = $socialize_user->user['gender'];
        $user->provider     = "facebook";
        $user->provider_id  = "1";
        $user->access_token = $socialize_user->token;
        $user->city         = $city;
        $user->country      = $country;
        //$user->save();

        $page_title = "Beerhit!";
        $page_descs = "what hit you?";
        $data = array('page_title' => $page_title,
                      'page_descs' => $page_descs,
                      'user'       => $user
                      );
        return view('edit.register_user',$data);
    }

    // login
    Auth::loginUsingId($user->id);

    return redirect("/profile/$user->username");
});