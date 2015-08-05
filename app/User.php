<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /*
     *  Get checkin by user
     * */
    public static function getUserCheckIn($user_id)
    {
        $data   =   DB::table('checkin')
                        ->groupBy('checkin.place_id')
                        ->where('checkin.user_id',$user_id)
                        ->join('places','checkin.place_id','=','places.place_id')
                        ->get();
        return $data;
    }

    /*
     * Getting userfriends using fb_id
     * */
    public static function userFriend($fb_id)
    {
        if(!empty($fb_id))
        {
            $fb =  \FacebookHelper::fb_init();
            $response = $fb->get("/me/friends", \Auth::user()->access_token);

            if(!empty($response)):
                $fb_response    = $response->getDecodedBody();

                $x=0;
                foreach($fb_response['data'] as $friend):
                    $fb_data[$x] = $friend['id'];
                    $x++;
                endforeach;

                $user_friends = DB::table('users')
                    ->whereIn('fb_id', $fb_data)
                    ->select('users.fb_id','users.username','users.avatar')
                    ->get();
                return $user_friends;
            endif;
        }
    }

}
