<?php

use App\UserBeer;
use App\Places;
use App\BeerImg;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

/**
 * User this class for activities between user and beer
 */
class UserBeerHelper {

    /*
     * Convert into readable way
     * take timestamp as string then convert to time
     * @return readable time stamp
     * */
    public static function humanTime($date)
    {
        return Carbon::createFromTimestamp(strtotime($date))->diffForHumans();
    }


    /*
     * Return FA icon regarding to the type ID
     * */
    public static function type_return_icon($type_id)
    {
        switch($type_id):
            case '101';
                return  "<i class=\"fa fa-beer fa-1x\"></i>";
                break;
            case '102';
                return  "<i class=\"fa fa-thumbs-o-up fa-1x\"></i>";
                break;
            case '103';
                break;
            case '200';
                return  "<i class=\"fa fa-picture-o fa-1x\"></i>";
                break;
            case '300';
                return  "<i class=\"fa fa-map-marker fa-1x\"></i>";
                break;
        endswitch;
    }
    /**
     *  Convert type ID into something meaningful for human
     */
    public static function type_return($type_id,$user_id,$beer_id,$text = null,$place_id=null,$img_id=null)
    {
        switch($type_id):

            //User had this drink
            case '101';
                return "had this drink!";
                break;

            //User like this
            case '102';
                return "like this";
                break;

            //User want this
            case '103';
                return "want this";
                break;

            //Image posted
            case '200';
                $data =  BeerImg::getImage($img_id);
                return " uploaded ". "<img src=\"$data->path\" class=\"img-responsive\">";
                break;

            //Check-in
            case '300';
                $data = Places::userBeerPlace($user_id,$beer_id,$place_id);
                return "check in ".GlobalUrl::place_url($data)." having ".$data->beer;
                break;

        endswitch;
    }

    public static function userLog($user_id,$beer_id,$type_id,$place_id=0,$img_id=0)
    {
        $user_meta = new \App\UserBeer();
        $user_meta->user_id     = $user_id;
        $user_meta->beer_id     = $beer_id;
        $user_meta->type_id     = $type_id;
        $user_meta->place_id    = $place_id;
        $user_meta->img_id      = $img_id;
        $user_meta->save();
    }

    /*
     * Check if the existing activity exist
     * */
    public static function checkUserLog($user_id,$beer_id,$type_id)
    {
        $check = \App\UserBeer::where('user_id',$user_id)
                                ->where('beer_id',$beer_id)
                                ->where('type_id',$type_id)->count();
        return $check;
    }


    public static function getRating($beer_id)
    {
        $data = DB::table('beer_ratings')
                        ->orderBy('beer_ratings.created_at', 'desc')
                        ->where('beer_ratings.beer_id',$beer_id)
                        ->join('users', 'beer_ratings.user_id', '=', 'users.id')->get();
        return $data;
    }


    public static function beerRating($beer_id)
    {
        ?>
        <div class="pull-right">
            <h1>9.5</h1>
            <p>Taste <progress value="8" max="10" class=""></progress> <label>8</label></p>
            <p>Look  <progress value="8" max="10"></progress></p>
            <p>Smell <progress value="8" max="10"></progress></p>
            <p>Feel  <progress value="8" max="10"></progress></p>
        </div>
        <?php
    }


    /*
     *  The verdict
     * */
    public static function beerRatingEdit($beer_id)
    {
        ?>

        <div class="padding_box">

            <textarea class="form-control" id="ratingComment"></textarea>

            <div data-role="main" class="ui-content">
                <form oninput="amount.value=rangeInput.value">
                    <div class="row">
                        <div class="col-xs-12 col-sm-2" style="font-size: 20px">Taste</div>
                        <div class="col-xs-12 col-sm-10">
                            <div class="col-xs-10" style="padding-top: 8px;">
                                <input type="range" id="tasteInput" name="rangeInput" min="0" max="10" step="0.5" class="pull-left">
                            </div>
                            <div class="col-xs-2">
                                <output class="pull-right" name="amount" for="rangeInput" style="font-size: 24px;padding-top: 0px;">0</output>
                            </div>
                        </div>
                    </div>
                </form>

                <form oninput="amount.value=rangeInput.value">
                    <div class="row">
                        <div class="col-xs-12 col-sm-2" style="font-size: 20px">Look</div>
                        <div class="col-xs-12 col-sm-10">
                            <div class="col-xs-10" style="padding-top: 8px;">
                                <input type="range" id="lookInput" name="rangeInput" min="0" max="10" step="0.5" class="pull-left">
                            </div>
                            <div class="col-xs-2">
                                <output class="pull-right" name="amount" for="rangeInput" style="font-size: 24px;padding-top: 0px;">0</output>
                            </div>
                        </div>

                    </div>
                </form>

                <form oninput="amount.value=rangeInput.value">
                    <div class="row">
                        <div class="col-xs-12 col-sm-2" style="font-size: 20px">Smell</div>
                        <div class="col-xs-12 col-sm-10">
                            <div class="col-xs-10" style="padding-top: 8px;">
                                <input type="range" id="smellInput" name="rangeInput" min="0" max="10" step="0.5" class="pull-left">
                            </div>
                            <div class="col-xs-2">
                                <output class="pull-right" name="amount" for="rangeInput" style="font-size: 24px;padding-top: 0px;">0</output>
                            </div>
                        </div>

                    </div>
                </form>

                <form oninput="amount.value=rangeInput.value">
                    <div class="row">
                        <div class="col-xs-12 col-sm-2" style="font-size: 20px">Feel</div>
                        <div class="col-xs-12 col-sm-10">
                            <div class="col-xs-10" style="padding-top: 8px;">
                                <input type="range" id="feelInput" name="rangeInput" min="0" max="10" step="0.5" class="pull-left">
                            </div>
                            <div class="col-xs-2">
                                <output class="pull-right" name="amount" for="rangeInput" style="font-size: 24px;padding-top: 0px;">0</output>
                            </div>
                        </div>

                    </div>
                </form>

            </div>

            <br>
            <input type="submit" class="form-control btn btn-primary" onclick="submitReview()">

            <script>
                function submitReview()
                {
//                    alert($('#tasteInput').val() + "-"+
//                          $('#lookInput').val() + "-"+
//                          $('#smellInput').val() + "-"+
//                          $('#feelInput').val()
//                         );
                    postBeer();
                }
                function postBeer()
                {
                    $.ajax({
                        headers:
                        {
                            'X-CSRF-Token': $('input[name="_token"]').val()
                        },
                        method: "POST",
                        url: "/ajax/submitReview",
                        data: {
                                rating_comments: $('#ratingComment').val(),
                                beer: <?php echo $beer_id?>,
                                taste: $('#tasteInput').val(),
                                  look: $('#lookInput').val(),
                                  smell: $('#smellInput').val(),
                                  feel: $('#feelInput').val()
                              }
                    })
                        .done(function( msg ) {
                            $('#beerRating_container').html(msg);
                        });
                }
            </script>
            <style>

            </style>
        </div>
        <?php
    }


    /*
     * To be use in the User profile page
     * */
    public static function beerRatingUser()
    {
        ?>
            <textarea class="form-control" name="ratingComment" id="ratingComment"></textarea>

            <div class="row">
                <div class="col-xs-12 col-sm-2" style="font-size: 20px">Taste</div>
                <div class="col-xs-12 col-sm-10">
                    <div class="col-xs-10" style="padding-top: 8px;">
                        <input type="range" id="tasteInput" name="tasteInput" min="0" max="10" step="0.5" class="pull-left"
                               oninput="inputAmount.value=tasteInput.value">
                    </div>
                    <div class="col-xs-2">
                        <output class="pull-right" name="inputAmount" for="tasteInput" style="font-size: 24px;padding-top: 0px;">0</output>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-2" style="font-size: 20px">Look</div>
                <div class="col-xs-12 col-sm-10">
                    <div class="col-xs-10" style="padding-top: 8px;">
                        <input type="range" id="lookInput" name="lookInput" min="0" max="10" step="0.5" class="pull-left"
                               oninput="lookAmount.value=lookInput.value">
                    </div>
                    <div class="col-xs-2">
                        <output class="pull-right" name="lookAmount" for="rangeInput" style="font-size: 24px;padding-top: 0px;">0</output>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-2" style="font-size: 20px">Smell</div>
                <div class="col-xs-12 col-sm-10">
                    <div class="col-xs-10" style="padding-top: 8px;">
                        <input type="range" id="smellInput" name="smellInput" min="0" max="10" step="0.5" class="pull-left"
                               oninput="smellAmount.value=smellInput.value">
                    </div>
                    <div class="col-xs-2">
                        <output class="pull-right" name="smellAmount" for="rangeInput" style="font-size: 24px;padding-top: 0px;">0</output>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-2" style="font-size: 20px">Feel</div>
                <div class="col-xs-12 col-sm-10">
                    <div class="col-xs-10" style="padding-top: 8px;">
                        <input  type="range" id="feelInput" name="feelInput" min="0" max="10" step="0.5" class="pull-left"
                                oninput="feelAmount.value=feelInput.value">
                    </div>
                    <div class="col-xs-2">
                        <output class="pull-right" name="feelAmount" for="rangeInput" style="font-size: 24px;padding-top: 0px;">0</output>
                    </div>
                </div>

            </div>
        <?php
    }

    /**
     * Take beer_id and return rating review in html format
     */
    public static function beerRatingContainer($beer_id)
    {
        $beer_ratings = \UserBeerHelper::getRating($beer_id);

        foreach($beer_ratings as $br):?>
        <div class="media">
            <div class="media-left">
                <?php echo \GlobalUrl::user_profile_pic($br->username, $br->avatar,'img-circle') ?>
            </div>
            <div class="media-body">

                <div class="pull-right">
                    <h2><?php  echo $br->overall;?></h2>
                </div>

                <div class="pull-left">
                    <ul class="nav nav-pills" role="tablist">
                        <?php echo $br->comment ?>
                    </ul>
                    <br>
                    <br>
                    <small>
                        <ul class="nav nav-pills" role="tablist">
                            <div valign="bottom">
                                Taste   <strong>  <?php echo $br->taste  ?></strong> &bull;
                                Look    <strong>  <?php echo  $br->look  ?></strong> &bull;
                                Smell   <strong>  <?php echo  $br->smell ?></strong> &bull;
                                Feel    <strong>  <?php echo  $br->feel  ?></strong>
                            </div>
                        </ul>
                    </small>
                </div>
            </div>
        </div>
        <hr>
        <?php
        endforeach;
    }

}?>