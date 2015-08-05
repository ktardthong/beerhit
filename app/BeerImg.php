<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic;

class beerImg  extends  Eloquent
{
    protected $table = 'beer_img';

    /*
     * Get beer image(s) from given beer_id
     * @return array
     * */
    public static function getBeerImg($beer_id)
    {
        $data = DB::table('beer_img')
                    ->where('beer_id',$beer_id)
                    ->get();
        return $data;
    }

    /*
     * Get img from image_id
     * */
    public static function getImage($img_id)
    {
        $data = DB::table('beer_img')
                    ->where('img_id',$img_id)
                    ->first();
        return $data;
    }

    public static function recentImg()
    {
        $data = DB::table('beer_img')
                    ->join('beers', 'beers.id', '=', 'beer_img.beer_id')
                    ->get();
        return $data;
    }

    /*
     * Get images from specific user
     * @param   $user_id
     * @return  $data
     * */
        public static function userUploaded($user_id)
    {
        $data = DB::table('beer_img')
                    ->where('beer_img.user_id',$user_id)
                    ->join('beers', 'beers.id', '=', 'beer_img.beer_id')
                    ->select('*')
                    ->get();
        return $data;
    }

    public static function uploadBeerImg_callback($request,$fileimage)
    {
        $image_unique_id = date('Ymdhis') . rand(0, 9999);

        $beer_meta = new BeerImg();

        $beer_meta->beer_id         = $request['beer_id'];
        $beer_meta->description     = !empty($request['img_description'])?$request['img_description']:null;
        $beer_name                  = !empty($request['img_description'])?$request['img_description']:null;

        $imageName = "beerhit.com_" . strtolower($beer_name) . $request['beer_id'] . $image_unique_id . '.' . $fileimage->getClientOriginalExtension();

        $path = public_path('/images/catalog/' . $imageName);
        $img = ImageManagerStatic::make($fileimage);

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
        $beer_meta->place_id    =   $request['fb_place_id'];
        $beer_meta->save();

        \UserBeerHelper::userLog(\Auth::user()->id,$request['beer_id'],200,$request['fb_place_id'],$image_unique_id);
    }
}